<?php     defined('C5_EXECUTE') or die("Access Denied.");
$al = Core::make('helper/concrete/asset_library');
print Core::make('helper/concrete/ui')->tabs(array(
      array('map-settings', t('Map Settings'), true),
      array('map-styling', t('Map Styling')),
      array('map-info', t('Add Info Window'))
));
?>
<div id="ccm-tab-content-map-settings" class="ccm-tab-content">
<div class="ccm-google-map-block-container row">       

    <div class="col-xs-12"> 
        <div class="form-group">
            <label class="control-label" for="apikey" name="apikey">Google Maps API key
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Paste your Google Maps API Key here. (Required as of 22 June 2016)'); ?>"></i>
            </label>
            <?php     echo $form->text('apikey', $mapObj->apikey);?>
        </div>
        
        <div class="form-group">
            <label class="control-label" for="title" name="title">Map Title (Optional)
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('If you would like to have a title (heading) above your map then add it here.'); ?>"></i>
            </label>
            <?php     echo $form->text('title', $mapObj->title);?>
        </div>

        <div id="ccm-google-map-block-location" class="form-group">
            <label class="control-label" for="location" name="location">Location
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Enter a location. This will become the focus of the map / map marker.') ?>"></i>
            </label>
            <?php     echo $form->text('location', $mapObj->location);?>
            <?php     echo $form->hidden('latitude', $mapObj->latitude);?>
            <?php     echo $form->hidden('longitude', $mapObj->longitude);?>
        </div>
            
        <div class="col-xs-12">
              <div id="block_note" class="alert alert-info" role="alert">
                 <?php     echo t('Start typing the name or address of a location then choose the correct option from the list.')?>
              </div>             
            <div id="map-canvas"></div>	
        </div>
    </div>

    <div class="col-xs-4">    
        <div class="form-group">
            <label class="control-label" for="zoom" name="zoom">Zoom
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Set the zoom level of your map. Select a number between 0 & 21 with 0 being world view and 21 being street level.') ?>"></i>
            </label>
            <?php    
                $zoomArray = array();
                for($i=0;$i<=21;$i++) {
                    $zoomArray[$i] = $i;
                }
            ?>
            <?php     echo $form->select('zoom', $zoomArray, $mapObj->zoom);?>
        </div>
    </div>

    <div class="col-xs-4">	
        <div class="form-group"> 
            <label class="control-label" for="width" name="width">Map Width
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Set the width of your map here.') ?>"></i>
            </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
                <?php     if(is_null($width) || $width == 0) {$width = '100%';};?>
                <?php     echo $form->text('width', $width);?>
            </div>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="form-group">
             <label class="control-label" for="height" name="height">Map Height
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Set the height of your map here.') ?>"></i>
            </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
                <?php     if(is_null($height) || $height == 0) {$height = '400px';};?>
                <?php     echo $form->text('height', $height); ?>
            </div>
        </div>
    </div> 

    <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <label class="control-label" for="scrollwheel" name="scrollwheel">                
                 Scroll Wheel
                 <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Select \'Yes\' if you would like your map to zoom in and out by moving the scroll wheel.') ?>"></i>
            </label>
            <?php    
               $scrollArray = array('yes'=>'Yes','no'=>'No');              
               echo $form->select('scrollwheel', $scrollArray, $mapObj->scrollwheel);
            ?>
        </div>
    </div> 
    
    <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <label class="control-label" for="satelliteControl" name="satelliteControl">                
                 Satellite View
                 <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Add Satellite view controls to your map. Users can toggle a satellite view of your map.') ?>"></i>
            </label>
            <?php    
               $satArray = array('yes'=>'Yes','no'=>'No');              
               echo $form->select('satelliteControl', $satArray, $mapObj->satelliteControl);
            ?>
        </div>
    </div> 
    
    <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <label class="control-label" for="streetControl" name="streetControl">                
                 Street View
                 <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Add street view controls to your map. Users can toggle a street view of your map') ?>"></i>
            </label>
            <?php    
                $streetArray = array('yes'=>'Yes','no'=>'No');              
                echo $form->select('streetControl', $streetArray, $mapObj->streetControl);
             ?>
        </div>
    </div>  
       
  </div><!-- / Row -->
</div> <!-- / map-settings tab -->    

<div id="ccm-tab-content-map-styling" class="ccm-tab-content">
<div class="ccm-google-map-block-container row">
    <div class="col-xs-12">
	    <div class="form-group">
	        <?php     $f = \File::getByID($mapObj->fID);?>		
		<label class="control-label" for="fID" name="fID">Add a custom map marker (optional)
                    <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Upload or select an image from the file manager that will be used to replace the default Google map marker.') ?>"></i>
                </label>
		<?php     echo $al->file('fID', 'fID', t('Choose image file'), $f);?>
	    </div>
	</div>
	
	<div class="col-xs-6">	
        <div class="form-group">            
            <label class="control-label" for="markerwidth" name="markerwidth">Custom Map Marker Width
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('If you added a custom map marker image above, then you can set a custom Width in pixels for it here. * Enter a number only') ?>"></i>
            </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
                <?php     if(is_null($markerwidth) || $markerwidth == 0) {$markerwidth = '50';};?>
                <?php     echo $form->text('markerwidth', $markerwidth);?>
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>	
    
    <div class="col-xs-6">
        <div class="form-group">
            <label class="control-label" for="markerheight" name="markerheight">Custom Map Marker Height
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('If you added a custom map marker image above, then you can set a custom Height in pixels for it here. * Enter a number only') ?>"></i>
            </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
                <?php     if(is_null($markerheight) || $markerheight == 0) {$markerheight = '50';};?>
                <?php     echo $form->text('markerheight', $markerheight); ?>
                <span class="input-group-addon">px</span>
            </div>
        </div>
    </div>
    
    <div class="col-xs-12">
        <div id="block_note" class="alert alert-info" role="alert"><?php     echo t('Choose from a standard theme option below (Basic)<br><i>or</i><br>Add a custom map style into the textarea for greater control (Advanced).')?></div>
    </div>
    
    <!-- Theme options - Added v0.9.1 -->
    <div class="col-xs-12">
        <div class="form-group">            
            <label class="control-label" for="themehex" name="themehex">Select a standard theme (Basic)
                <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('To add a standard theme just choose an option from the dropdown list below. If you plan to add custom Snazzy Maps code then make sure to select - Custom (Advanced) - from the list') ?>"></i>
            </label>
            <?php    
                $hexArray = array('ff0000'=>'Red',
                		  'ff7000'=>'Orange',
                	          'ffeb00'=>'Yellow',
                	          '00ff39'=>'Green' ,
                	          '0095ff'=>'Blue' ,
                	          'bd00ff'=>'Purple',
                	          'ff00d4'=>'Pink',
                	          '222222'=>'Greyscale',
                	          'google'=>'Default',
                	          'custom'=>'Custom (Advanced)'
                	           );              
              
            ?>
            <?php     echo $form->select('themehex', $hexArray, $mapObj->themehex);?>
        </div>
    </div><br>
        
	<div class="col-xs-12" id="showHideAdvanced">
            <div class="form-group">               
                <label class="control-label" for="snazzycode" name="snazzycode">Custom Snazzy Maps code (Advanced : Optional)
                    <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Use the - Get Snazzy Maps Styles Here - link below to search or create custom styles from the Snazzy Maps website. Once you have the style you would like to use, copy the code and then paste it in here. Make sure the code begins and ends with square brackets [].') ?>"></i>
                </label>                          
                <?php     echo $form->textarea('snazzycode', $mapObj->snazzycode, array('rows' => 9));?>                
            </div> 
            <small><?php     echo t('Get Snazzy Maps styles '); ?><a href="https://snazzymaps.com/" target="_blank"><?php     echo t('Here'); ?></a></small>       
	</div>
  </div><!-- / Row -->  
</div> <!-- / map-styling tab -->

<div id="ccm-tab-content-map-info" class="ccm-tab-content">
  <div class="ccm-google-map-block-container row">  
    <div class="col-xs-12">
        <div id="block_note" class="alert alert-info" role="alert"><?php     echo t('The below option allows you to add a popup info window to your map.<br><br>You have the option to choose if the info window is displayed when the map marker is clicked or when the map loads.<br><br>Great for opening hours, links or any other relevant information.<br><br><i><small>* Leave this blank if you do not require an info window</small></i>')?></div>
    </div>
  
    <div class="col-xs-12">
        <div class="form-group">          
            <label class="control-label" for="info window" name="info window">Info Window Content (Optional)
              <i class="launch-tooltip fa fa-question-circle" 
              title="<?php     echo t('An Info Window displays content (usually text or images) in a popup window above the map.') ?>"></i>
            </label>           
            <?php    
                $editor = Core::make('editor');
                echo $editor->outputStandardEditor('infotext', $mapObj->infotext);
            ?>
         </div>
     </div> 
     
     <div class="col-xs-12">
        <div class="form-group">
            <label class="control-label" for="infostate" name="infostate">
                <?php     echo $form->checkbox('infostate', 1, (is_null($infostate) || $infostate)); ?>
                 Display Info Window When Map Marker Clicked
                 <i class="launch-tooltip fa fa-question-circle" title="<?php     echo t('Check this option if you would like the info window to display only when the map marker is clicked. If unchecked the info window will be displayed when the map is loaded.') ?>"></i>
            </label>
        </div>
    </div>       
  </div><!-- / Row -->
</div> <!-- / map-info tab -->

<script type="text/javascript">
$(function() {
    window.C5GMaps.init();
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $("select").change(function(){
        $(this).find("option:selected").each(function(){
            if($(this).attr("value")=="custom"){
               
                $("#showHideAdvanced").show();
            }
            else if($(this).attr("value")!="custom"){
                
                $("#showHideAdvanced").hide();
            }
        });
    }).change();
});
</script>