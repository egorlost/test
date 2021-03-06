<?php

/**
 * Podium Module
 * Yii 2 Forum Module
 * @author Paweł Bizley Brzozowski <pb@human-device.com>
 * @since 0.1
 */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::t('podium/view', 'Move Posts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('podium/view', 'Main Forum'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['default/category', 'id' => $category->id, 'slug' => $category->slug]];
$this->params['breadcrumbs'][] = ['label' => $forum->name, 'url' => ['default/forum', 'cid' => $forum->category_id, 'id' => $forum->id, 'slug' => $forum->slug]];
$this->params['breadcrumbs'][] = ['label' => $thread->name, 'url' => ['default/thread', 'cid' => $thread->category_id, 'fid' => $thread->forum_id, 'id' => $thread->id, 'slug' => $thread->slug]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <strong><?= Yii::t('podium/view', 'Select posts to move') ?></strong>:
            </div>
        </div>
    </div>
</div><br>

<?= Html::beginForm(); ?>
<?php Pjax::begin(); ?>
<?= ListView::widget([
    'dataProvider'     => $dataProvider,
    'itemView'         => '/elements/forum/_post_select',
    'summary'          => '',
    'emptyText'        => Yii::t('podium/view', 'No posts have been added yet.'),
    'emptyTextOptions' => ['tag' => 'h3', 'class' => 'text-muted'],
    'pager'            => ['options' => ['class' => 'pagination pull-right']]
]);  ?>
<?php Pjax::end(); ?>
    <br>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= Html::label(Yii::t('podium/view', 'Select a thread for this posts to be moved to'), 'newthread') ?>
                                <p>* <?= Yii::t('podium/view', 'Forums you can moderate are marked with asterisk.') ?></p>
                                <?= Html::dropDownList('newthread', null, $list, ['id' => 'newthread', 'class' => 'form-control', 'options' => $options, 'encode' => false]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= Html::label(Yii::t('podium/view', 'Name of the new thread'), 'newname') ?>
                                <?= Html::textInput('newname', null, ['id' => 'newname', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= Html::label(Yii::t('podium/view', 'Parent forum of the new thread'), 'newforum') ?>
                                <p>* <?= Yii::t('podium/view', 'Forums you can moderate are marked with asterisk.') ?></p>
                                <?= Html::dropDownList('newforum', null, $listforum, ['id' => 'newforum', 'class' => 'form-control', 'encode' => false]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::submitButton('<span class="glyphicon glyphicon-ok-sign"></span> ' . Yii::t('podium/view', 'Move Posts'), ['class' => 'btn btn-block btn-primary', 'name' => 'save-button']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
<?= Html::endForm();
