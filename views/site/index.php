<?php
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\models\Links;
$this->title = 'Тестовое задание';
$links = Links::find()
    ->indexBy('id')
    ->all();
?>
<div class="login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Сокращение ссылки:</p>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>
        <?= $form->field($model, 'shorted_link')->textInput()->hint('Введите Вашу ссылку')->label('Ссылка') ?>
        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Сократить', ['class' => 'btn btn-primary', 'name' => 'button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    <div id="short_link" class="hidden"><p>Ваша ссылка:</p><h2 id="copy" style="color: #2fa3e7;font-size: 16px;"></h2></div>
    <div class="copy_link_mess">Ссылка скопированна</div>
</div>
<br />
<table class="table">
    <thead>
        <tr>
            <th>Месяц</th>
            <th>Ссылка</th>
            <th>Кол-во переходов</th>
            <th>Позиция в топе месяца по переходам</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($links as $link): ?>
            <tr>
                <td><?php echo $link->month; ?></td>
                <td><?php echo $link->shorted_link; ?></td>
                <td><?php echo $link->cliks; ?></td>
                <td><?php echo $link->top_position; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<style>
    .copy_link_mess{
        line-height:40px;
        border-radius:5px;
        background:#1DA1F2;
        color:#fff;
        position:fixed;
        padding:0 15px;
        z-index:99;
        bottom:30px;
        left:50%;
        margin-left:-120px;
        display:none;
        box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 15px;
    }
    .hidden {
        display:none;
    }
</style>
<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
        $('#copy').empty();
        $('#short_link').hide();
        var data = $(this).serialize();
        $.ajax({
            url: 'site/index',
            type: 'POST',
            data: data,
            success: function(res){
                var origin = window.location.origin
                $( "#short_link" ).show();
                $('#copy').append(origin+"/"+res);
                console.log(res);
            },
            error: function(){
                alert('Error!');
            }
        });
        return false;
    });
    $(document).ready(function($){
        $('#copy').click(function() {
            var text_copy = $(this);
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(text_copy.text()).select();
            document.execCommand("copy");
            temp.remove();
            $('.copy_link_mess').fadeIn(400);
            setTimeout(function(){
                $('.copy_link_mess').fadeOut(400);
            },5000);
        });
    });
JS;
$this->registerJs($js);
?>