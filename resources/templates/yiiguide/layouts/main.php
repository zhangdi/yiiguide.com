<?php

use App\Assets\AssetBundle;
use App\Assets\JsSearchAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */

AssetBundle::register($this);

// Navbar hides initial content when jumping to in-page anchor
// https://github.com/twbs/bootstrap/issues/1768
$this->registerJs(<<<JS
    var shiftWindow = function () { scrollBy(0, -50) };
    if (location.hash) setTimeout(shiftWindow, 1);
    window.addEventListener("hashchange", shiftWindow);
JS
    ,
    \yii\web\View::POS_READY
);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="en"/>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <title><?php if (isset($type)) {
            echo Html::encode(StringHelper::basename($type->name) . ", {$type->name} - {$this->context->pageTitle}");
        } elseif (isset($guideHeadline)) {
            echo Html::encode("$guideHeadline - {$this->context->pageTitle}");
        } else {
            echo Html::encode($this->context->pageTitle);
        }
        ?></title>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => $this->context->pageTitle,
        'brandUrl' => ($this->context->apiUrl === null && $this->context->guideUrl !== null) ? './guide-README.html' : './index.html',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        'renderInnerContainer' => false,
        'view' => $this,
    ]);
    $nav = [];

    if ($this->context->apiUrl !== null) {
        $nav[] = ['label' => 'Class reference', 'url' => rtrim($this->context->apiUrl, '/') . '/index.html'];
        if (!empty($this->context->extensions)) {
            $extItems = [];
            foreach ($this->context->extensions as $ext) {
                $extItems[] = [
                    'label' => $ext,
                    'url' => "./ext-{$ext}-index.html",
                ];
            }
            $nav[] = ['label' => 'Extensions', 'items' => $extItems];
        }
    }

    if ($this->context->guideUrl !== null) {
        $nav[] = ['label' => '中文权威指南', 'url' => rtrim($this->context->guideUrl, '/') . '/' . $this->context->guidePrefix . 'README.html'];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $nav,
        'view' => $this,
        'params' => [],
    ]);
    ?>
    <div class="navbar-form navbar-left" role="search">
        <div class="form-group">
            <input id="searchbox" type="text" class="form-control" placeholder="搜索">
        </div>
    </div>
    <?php
    JsSearchAsset::register($this);

    // defer loading of the search index: https://developers.google.com/speed/docs/best-practices/payload?csw=1#DeferLoadingJS
    $this->registerJs(<<<JS
var element = document.createElement("script");
element.src = "./jssearch.index.js";
document.body.appendChild(element);
JS
    );

    $this->registerJs(<<<JS

var searchBox = $('#searchbox');

// search when typing in search field
searchBox.on("keyup", function(event) {
    var query = $(this).val();

    if (query == '' || event.which == 27) {
        $('#search-resultbox').hide();
        return;
    } else if (event.which == 13) {
        var selectedLink = $('#search-resultbox a.selected');
        if (selectedLink.length != 0) {
            document.location = selectedLink.attr('href');
            return;
        }
    } else if (event.which == 38 || event.which == 40) {
        $('#search-resultbox').show();

        var selected = $('#search-resultbox a.selected');
        if (selected.length == 0) {
            $('#search-results').find('a').first().addClass('selected');
        } else {
            var next;
            if (event.which == 40) {
                next = selected.parent().next().find('a').first();
            } else {
                next = selected.parent().prev().find('a').first();
            }
            if (next.length != 0) {
                var resultbox = $('#search-results');
                var position = next.position();

//              TODO scrolling is buggy and jumps around
//                resultbox.scrollTop(Math.floor(position.top));
//                console.log(position.top);

                selected.removeClass('selected');
                next.addClass('selected');
            }
        }

        return;
    }
    $('#search-resultbox').show();
    $('#search-results').html('<li><span class="no-results">未找到数据</span></li>');

    var result = jssearch.search(query);

    if (result.length > 0) {
        var i = 0;
        var resHtml = '';

        for (var key in result) {
            if (i++ > 20) {
                break;
            }
            resHtml = resHtml +
            '<li><a href="' + result[key].file.u.substr(3) +'"><span class="title">' + result[key].file.t + '</span>' +
            '<span class="description">' + result[key].file.d + '</span></a></li>';
        }
        $('#search-results').html(resHtml);
    }
});

// hide the search results on ESC
$(document).on("keyup", function(event) { if (event.which == 27) { $('#search-resultbox').hide(); } });
// hide search results on click to document
$(document).bind('click', function (e) { $('#search-resultbox').hide(); });
// except the following:
searchBox.bind('click', function(e) { e.stopPropagation(); });
$('#search-resultbox').bind('click', function(e) { e.stopPropagation(); });

JS
    );

    NavBar::end();
    ?>

    <div id="search-resultbox" style="display: none;" class="modal-content">
        <ul id="search-results">
        </ul>
    </div>

    <?= $content ?>

</div>

<footer class="footer">
    <p class="pull-left">&copy; yiiguide.com <?= date('Y') ?> <?= Yii::powered() ?></p>
    <p class="pull-right">
        <small>页面生成于 <?= date('Y/m/d H:i') ?></small>
    </p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
