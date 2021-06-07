<?php

namespace hipanel\modules\ipam\widgets;

use hipanel\assets\TreeTable;
use hipanel\modules\ipam\grid\PrefixGridView;
use hipanel\modules\ipam\models\Prefix;
use hipanel\modules\ipam\models\PrefixSearch;
use Yii;
use yii\base\Widget;
use yii\data\DataProviderInterface;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

class TreeGrid extends Widget
{
    public DataProviderInterface $dataProvider;

    public array $columns;

    public bool $showAll = true;

    public bool $includeSuggestions = false;

    public ?Prefix $parent = null;

    /**
     * @var array|string
     */
    public $url = 'get-tree-grid-rows';

    public function init(): void
    {
        TreeTable::register($this->view);
    }

    public function run(): string
    {
        $this->registerJs();
        $this->registerCss();

        return PrefixGridView::widget([
            'boxed' => false,
            'parent' => $this->parent,
            'dataProvider' => $this->dataProvider,
            'layout' => '{items}{pager}',
            'filterModel' => new PrefixSearch(),
            'rowOptions' => static fn(Prefix $prefix, $key): array => [
                'data' => [
                    'key' => $prefix->id,
                    'tt-id' => $prefix->id,
                    'tt-parent-id' => $prefix->parent_id ?? 0,
                    'tt-branch' => $prefix->child_count == 0 ? 'false' : 'true',
                    'is-suggested' => $prefix->isSuggested() ? 1 : 0,
                ],
                'class' => sprintf("%s", $prefix->isSuggested() ? 'success' : ''),
            ],
            'tableOptions' => ['id' => $this->getId(), 'class' => 'table table-striped table-bordered'],
            'filterRowOptions' => ['style' => 'display: none;'],
            'columns' => $this->columns,
        ]);
    }

    private function registerJs(): void
    {
        $options = Json::encode(empty($this->pluginOptions) ? $this->getDefaultPluginOptions() : $this->pluginOptions);
        $includeSuggestions = Json::htmlEncode($this->includeSuggestions ? 1 : 0);
        $id = $this->getId();
        $url = $this->url;
        $csrfName = Yii::$app->request->csrfParam;
        $csrfToken = Yii::$app->request->csrfToken;
        $this->view->registerJs("(() => {
            const grid = $('#$id');
            const bulkBtns = $(document).find('.btn-bulk');
            grid.find(':checkbox').on('change', function () {
                let cnt = grid.find(':checkbox').filter(':checked').length;
                if (cnt > 0) {
                    bulkBtns.attr('disabled', false);
                }
                if (cnt === 0) {
                    bulkBtns.attr('disabled', true);
                }
            });
            bulkBtns.on('click', function (event) {
                event.preventDefault();
                const selection = [];
                grid.find(':checkbox').filter(':checked').each(function () {
                    if (this.name === 'selection_all') {
                      return;
                    }
                    const row = $(this).parent().closest('tr');
                    if (row.data('isSuggested') == 0) {
                        selection.push(row.data('key'));
                    }
                });
                bulkBtns.attr('disabled', true);
                grid.find(':checkbox').filter(':checked').removeAttr('checked');
                if (selection.length === 0) {
                  return;
                }
                const form = document.createElement('form');
                document.body.appendChild(form);
                form.method = 'POST';
                form.action = event.target.dataset.action;
                function addInput(name, value) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                }
                addInput('$csrfName', '$csrfToken');
                selection.forEach((id) => {
                    addInput('selection[]', id);
                });
                form.submit();
            });
        })()", View::POS_LOAD);
        if ($this->showAll) {
            $this->view->registerJs("
              $('#{$id}').treetable({$options});
              $('#{$id}').treetable('expandAll');
            ", View::POS_LOAD);
        } else {
            $this->view->registerJs("
              const tte_{$id} = function() {
                const parentNodeId = this.id;
                const tt = $('#{$id}');
                if (this.children.length > 0) {
                  return;
                }
                $.ajax({
                  url: '{$url}',
                  type: 'GET',
                  dataType: 'json',
                  data: { id: parentNodeId, includeSuggestions: {$includeSuggestions}},
                  beforeSend: function () {
                    $('.overlay').show();
                  },
                  complete: function () {
                    $('.overlay').hide();
                  },
                  success: rows => {
                    const parentNode = tt.treetable('node', parentNodeId);
                    if (Object.keys(rows).length) {
                      for (const [key, row] of Object.entries(rows)) {
                        const id = row.match(/data-tt-id=\"(\d+)\"/)[1];
                        if (!tt.treetable('node', parseInt(id))) {
                          tt.treetable('loadBranch', parentNode, row);
                        }
                      }
                    }
                  },
                  error: error => {
                    console.log(error);
                    $('.overlay').hide();
                  }
                });
              }
              $('#{$id}').treetable({$options});
            ", View::POS_LOAD);
        }
    }

    private function getDefaultPluginOptions(): array
    {
        $options = [
            'expandable' => true,
            'indent' => 11,
            'expanderTemplate' => '<a href="#" class="fa fa-fw">&nbsp;</a>',
        ];
        if (!$this->showAll) {
            $options['onNodeExpand'] = new JsExpression("tte_{$this->getId()}");
        }

        return $options;
    }

    private function registerCss(): void
    {
        $this->view->registerCss(<<<'CSS'

table.treetable tr.collapsed span.indenter a:before {
    content: "\f0da";
}

table.treetable tr.expanded span.indenter a:before {
    content: "\f0d7";
}
.tab-content > .overlay, .box-body > .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 50;
    background: rgba(255,255,255,0.7);
    border-radius: 3px
}
.tab-content > .overlay > .fa, .box-body > .overlay > .fa {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -15px;
    margin-top: -15px;
    color: darkgrey;
    font-size: 30px
}
CSS
        );
    }
}
