<?php


namespace App\Build\Controllers;

use Yii;


class GuideController extends \yii\apidoc\commands\GuideController
{
    /**
     * @var string 页面标题
     */
    public $pageTitle = 'YiiGuide 中文文档';
    /**
     * @var string 模板
     */
    public $template = 'App\Template\GuideRenderer';
    /**
     * @var bool 覆盖已有文档
     */
    public $overwrite = false;


    /**
     * @inheritDoc
     */
    protected function normalizeTargetDir($target)
    {
        $target = rtrim(Yii::getAlias($target), '\\/');
        if (file_exists($target)) {
            if (is_dir($target) && !$this->overwrite && !$this->confirm('TargetDirectory already exists. Overwrite?', true)) {
                $this->stderr('User aborted.' . PHP_EOL);

                return false;
            }
            if (is_file($target)) {
                $this->stderr("Error: Target directory \"$target\" is a file!" . PHP_EOL);

                return false;
            }
        } else {
            mkdir($target, 0777, true);
        }

        return $target;
    }

    /**
     * @inheritDoc
     */
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['overwrite']);
    }
}
