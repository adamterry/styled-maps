<?php    
namespace Concrete\Package\StyledMaps;

defined('C5_EXECUTE') or die('Access Denied.');

use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;

class Controller extends Package
{
    protected $pkgHandle = 'styled_maps';
    protected $appVersionRequired = '5.7.5';
    protected $pkgVersion = '1.2.3';

    public function getPackageDescription()
    {
        return t('Add a styled Google map to your website.');
    }

    public function getPackageName()
    {
        return t('Styled Maps');
    }

    public function install()
    {
        $pkg = parent::install();
        BlockType::installBlockType('styled_maps', $pkg);
    }
}
?>