<?php     defined('C5_EXECUTE') or die("Access Denied.");

$c = Page::getCurrentPage();
if ($c->isEditMode()) { ?>
	<div class="ccm-edit-mode-disabled-item" style="width: <?php     echo $width; ?>; height: <?php     echo $height; ?>">
		<div style="padding: 80px 0px 0px 0px"><?php     echo t('Styled Maps disabled in edit mode.')?></div>
	</div>
<?php      } else { ?>
	<?php      if( strlen($title)>0){ ?><h3><?php     echo $title?></h3><?php      } ?>
	<div id="googleMapCanvas<?php     echo $unique_identifier?>" class="googleMapCanvas" style="width: <?php     echo $width; ?>; height: <?php     echo $height; ?>"></div>
<?php      } ?>



<?php    
/*
    Note - this goes in here because it's the only way to preserve block caching for this block. We can't
    set these values through the controller
*/
?>

<script type="text/javascript">
    function googleMapInit<?php     echo $unique_identifier?>() {
        try{
            var latlng = new google.maps.LatLng(<?php     echo $latitude?>, <?php     echo $longitude?>);
            var mapOptions = {
                zoom: <?php     echo $zoom?>,
                center: latlng,                     
                <?php   
                if($satelliteControl == 'yes') { ?>           
                mapTypeControl: true,
                <?php   
                }
                else {
                ?>
                mapTypeControl: false,
                <?php   
                }
                ?>
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                <?php    if($streetControl == 'yes') { ?>
                streetViewControl: true,                
                <?php   
                }
                else {
                ?>
                streetViewControl: false,
                <?php   
                }
                if($scrollwheel == 'yes') { ?>
                scrollwheel: true,
                <?php   
                }
                else {
                ?>
                scrollwheel: false,
                <?php   
                }
                ?>
                zoomControl: true,
                <?php    
                    // Check for custom styling code (Advanced options)
		    if($snazzycode != "" && $themehex == "custom")
		    {
		        echo 'styles: '.$snazzycode;
		    }
		    // else if 'Default' theme selected from dropdown, Default to standard Google map styling
		    elseif($themehex == 'google') {
		    	echo '';
		    }
		    // else display the chosen theme (Basic option)
		    else
		    { ?>
		    
		        
		        styles: [
                                  {
                                    "featureType": "all",
                                    "elementType": "all",
                                    "stylers": [
                                                  {
                                                    "hue": "#<?php     echo $themehex; ?>"
                                                  },
                                                  {
                                                    <?php    
                                                        // If basic theme is Greyscale then set the saturation to -100
                                                        if($themehex == '222222'){
                                                    ?>    
                                                            "saturation": "-100"
                                                    <?php    
                                                    	}
                                                    	else { 
                                                    ?>
                                                    	    "saturation": "0"	
                                                    <?php    
                                                        }
                                                    ?>    	            
                                                  }
                                               ]
                                  }
                                ]
               <?php     } ?>
            };
            var map = new google.maps.Map(document.getElementById('googleMapCanvas<?php     echo $unique_identifier?>'), mapOptions);
            var customicon = {
                               <?php     if($fID > 0) { $f = \File::getByID($fID); ?>
                               url: <?php     echo "'".$f->getURL()."'"; ?>,
                               scaledSize : new google.maps.Size(<?php     echo $markerwidth.",".$markerheight; ?>)
                               <?php     } ?> 
                             };
            var marker = new google.maps.Marker({
                position: latlng,
                <?php    
                if($fID > 0)
                {
                    echo "map: map, icon: customicon,";
                }
                else
                {
                    echo 'map: map,';
                }
                ?>                                                              
            });
            
            <?php    
            // Add info window popup if text available
            if($infotext != "") {
            ?>
                 var contentString = "<?php     echo $infotext; ?>";

                 var infowindow = new google.maps.InfoWindow({
                     content: contentString
                 });
                 <?php     
                 // Check info window display state (Show window or wait for user to click marker)
                 if($infostate == 1) {
                 ?>
                     marker.addListener('click', function() {
                     infowindow.open(map, marker);
                     });
                 <?php    
                 }
                 else { ?>
                     infowindow.open(map, marker);
                     
                     marker.addListener('click', function() {
                     infowindow.open(map, marker);
                     });
                 <?php    
                 }
             } // End if $infotext
             ?>
                                   
        }catch(e){
            $("#googleMapCanvas<?php     echo $unique_identifier?>").replaceWith(e.message)}
    }
    $(function() {
        var t;
        var startWhenVisible = function (){
            if ($("#googleMapCanvas<?php     echo $unique_identifier?>").is(":visible")){
                window.clearInterval(t);
                googleMapInit<?php     echo $unique_identifier?>();
                return true;
            }
            return false;
        };
        if (!startWhenVisible()){
            t = window.setInterval(function(){startWhenVisible();},100);
        }
    });
</script>