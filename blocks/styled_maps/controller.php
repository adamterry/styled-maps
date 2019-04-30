<?php    

namespace Concrete\Package\StyledMaps\Block\StyledMaps;

use Page;
use Concrete\Core\Block\BlockController;
use Core;

class Controller extends BlockController
{
    protected $btTable = 'btStyledMaps';
    protected $btInterfaceWidth = "520";
    protected $btInterfaceHeight = "780";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btDefaultSet = 'multimedia';

    public $apikey = "";
    public $title = "";
    public $location = "";
    public $latitude = "";
    public $longitude = "";
    public $scrollwheel = "";
    public $satelliteControl = "";
    public $streetControl = "";
    public $zoom = 14;
    public $themehex = "";
    public $snazzycode = "";
    public $infotext = "";
    public $infostate = true;
    public $fID = "";

    /**
     * Used for localization. If we want to localize the name/description we have to include this.
     */
    public function getBlockTypeDescription()
    {
        return t("Add a styled Google map to your website.");
    }

    public function getBlockTypeName()
    {
        return t("Styled Maps");
    }
    
    public function getBlockTypeHelp()
    {
        return t("<p>Styled maps allows you to place a Google map anywhere on your site and apply a custom theme and location marker.</p>
                  <p>From beginners to experienced users, Styled maps makes customising your Google maps both quick and easy.</p>
                  <p>Use one of the standard themes or take total control and add an advanced / custom map styling by utilising the great resouces available at Snazzy Maps.</p>
                  <p>Add your own custom map marker for limitless design options.</p>
                  <p>For further information and documentation see <a href='http://www.cfive.advite.com.au/documentation/styled-maps-docs' target='_blank'>here</a>.</p>");
    }

    public function validate($args)
    {
        $error = Core::make('helper/validation/error');

        if (empty($args['location']) || $args['latitude'] === '' || $args['longtitude'] === '') {
            $error->add(t('You must select a valid location.'));
        }

        if (!is_numeric($args['zoom'])) {
            $error->add(t('Please enter a zoom number from 0 to 21.'));
        }
        
        // Update v0.9.1
        // Make sure map marker width & height is numeric only
        if (!is_numeric($args['markerwidth']) || !is_numeric($args['markerheight'])) {
             $error->add(t('Please make sure the map marker width and heights are a numerical value.'));
        }
        
        // Update v0.9.1
        // Add validation for Snazzy maps custom code to make sure any pasted code is contained between square brackets []
        if(!empty($args['snazzycode'])) {
           if(substr($args['snazzycode'], 0,1) != '[' || substr($args['snazzycode'], -1) != ']') {
            $error->add(t('Please make sure your custom code is correct and is contained between square brackets [].'));
           } 
        }
        
        // Update v0.9.1
        // Make sure Custom code has been added to the form if "Custom" theme option was selected from the dropdown menu
        if(empty($args['snazzycode']) && $args['themehex'] == 'custom') {
            $error->add(t('Please add custom theme code or choose a standard theme from the list.'));
        }
        
        // Check API Key has been supplied
        if(empty($args['apikey'])) {
            $error->add(t('Please enter your Google maps API key.'));
        }

        // Check for errors 
        if ($error->has()) {
            return $error;
        }
                
    } 


    public function view()
    {
        $this->set('unique_identifier', Core::make('helper/validation/identifier')->getString(18));
        $this->set('bID', $this->bID);
        $this->set('apikey', $this->apikey);
        $this->set('title', $this->title);
        $this->set('location', $this->location);
        $this->set('latitude', $this->latitude);
        $this->set('longitude', $this->longitude);
        $this->set('zoom', $this->zoom);
        $this->set('scrollwheel', $this->scrollwheel);
        $this->set('satelliteControl', $this->satelliteControl);
        $this->set('streetControl', $this->streetControl);
        $this->set('themehex', $this->themehex);
	$this->set('snazzycode', $this->snazzycode);
	$this->set('infotext', $this->infotext);
	$this->set('infostate', $this->infostate);
	$this->set('fID', $this->fID);
    }
    
    public function registerViewAssets($outputContent = '')
    {
        // If no API key (initial setup) use default key
        if(!$this->apikey) {
            $this->requireAsset('javascript', 'jquery');
            $this->addFooterItem(
               '<script src="https://maps.googleapis.com/maps/api/js?key=ADD_KEY_HERE&libraries=places"></script>'
            );
        }
        // otherwise access Google maps via user defined API key
        else {
            $api = $this->apikey;
            $this->requireAsset('javascript', 'jquery');
            $this->addFooterItem(
               '<script src="https://maps.googleapis.com/maps/api/js?key='.$api.'&libraries=places"></script>'
            );
        }    
    }

    public function save($data)
    {
        $data += array(
           'apikey' => '',
           'title' => '',
           'location' => '',
           'zoom' => -1,
           'latitude' => 0,
           'longitude' => 0,
           'width' => null,
           'scrollwheel' => '',
           'satelliteControl' => '',
           'streetControl' => '',
           'themehex' => '',
	   'snazzycode' => '',
	   'infotext' => '',
	   'infostate' => 0,
	   'fID' => 0,
        );
        $args['apikey'] = trim($data['apikey']);
        $args['title'] = trim($data['title']);
        $args['location'] = trim($data['location']);
        $args['zoom'] = (intval($data['zoom']) >= 0 && intval($data['zoom']) <= 21) ? intval($data['zoom']) : 14;
        $args['latitude'] = is_numeric($data['latitude']) ? $data['latitude'] : 0;
        $args['longitude'] = is_numeric($data['longitude']) ? $data['longitude'] : 0;
        $args['width'] = $data['width'];
        $args['height'] = $data['height'];
        $args['scrollwheel'] = trim($data['scrollwheel']);
        $args['satelliteControl'] = trim($data['satelliteControl']);
        $args['streetControl'] = trim($data['streetControl']);
        $args['markerwidth'] = $data['markerwidth'];
        $args['markerheight'] = $data['markerheight'];
        $args['themehex'] = trim($data['themehex']);
	$args['snazzycode'] = trim($data['snazzycode']);
	$args['infotext'] = str_replace('"', "'", $data['infotext']);
	$args['infotext'] = trim($args['infotext']);
	$args['infotext'] = preg_replace( "/\r|\n/", "", $args['infotext']);
	$args['infostate'] = $data['infostate'] ? 1 : 0;
	$args['fID'] = is_numeric($data['fID']) ? $data['fID'] : 0;
        parent::save($args);
    }
}
