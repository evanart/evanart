<?php
/**
 *     Jquery accordion slideshow
 *     Copyright (C) 2011 - 2013 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	
?>

<div class="wrap">
  <?php
  	global $wpdb;
    $title = __('Jquery accordion slideshow');
    @$mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=jquery-accordion-slideshow/jquery-accordion-slideshow.php";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
    @$submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['JaS_timeout']) <>"")
    {
			if($_POST['JaS_id'] == "" )
			{
					$sql = "insert into ".WpJqueryAccordionSlidshowTbl.""
					. " set `JaS_Gallery` = '" . mysql_real_escape_string(trim($_POST['JaS_Gallery']))
					. "', `JaS_Location` = '" . mysql_real_escape_string(trim($_POST['JaS_Location']))
					. "', `JaS_timeout` = '" . mysql_real_escape_string(trim($_POST['JaS_timeout']))
					. "', `JaS_width` = '" . mysql_real_escape_string(trim($_POST['JaS_width']))
					. "', `JaS_height` = '" . mysql_real_escape_string(trim($_POST['JaS_height']))
					. "', `JaS_slideWidth` = '" . mysql_real_escape_string(trim($_POST['JaS_slideWidth']))
					. "', `JaS_slideHeight` = '" . mysql_real_escape_string(trim($_POST['JaS_slideHeight']))
					. "', `JaS_tabWidth` = '" . mysql_real_escape_string(trim($_POST['JaS_tabWidth']))
					. "', `JaS_Random` = '" . mysql_real_escape_string(trim($_POST['JaS_Random']))
					. "', `JaS_speed` = '" . mysql_real_escape_string(trim($_POST['JaS_speed']))
					. "', `JaS_trigger` = '" . mysql_real_escape_string(trim($_POST['JaS_trigger']))
					. "', `JaS_pause` = '" . mysql_real_escape_string(trim($_POST['JaS_pause']))
					. "', `JaS_invert` = '" . mysql_real_escape_string(trim($_POST['JaS_invert']))
					. "', `JaS_easing` = '" . mysql_real_escape_string(trim($_POST['JaS_easing']))
					. "'";	
			}
			else
			{
					$sql = "update ".WpJqueryAccordionSlidshowTbl.""
					. " set `JaS_Gallery` = '" . mysql_real_escape_string(trim($_POST['JaS_Gallery']))
					. "', `JaS_Location` = '" . mysql_real_escape_string(trim($_POST['JaS_Location']))
					. "', `JaS_timeout` = '" . mysql_real_escape_string(trim($_POST['JaS_timeout']))
					. "', `JaS_width` = '" . mysql_real_escape_string(trim($_POST['JaS_width']))
					. "', `JaS_height` = '" . mysql_real_escape_string(trim($_POST['JaS_height']))
					. "', `JaS_slideWidth` = '" . mysql_real_escape_string(trim($_POST['JaS_slideWidth']))
					. "', `JaS_slideHeight` = '" . mysql_real_escape_string(trim($_POST['JaS_slideHeight']))
					. "', `JaS_tabWidth` = '" . mysql_real_escape_string(trim($_POST['JaS_tabWidth']))
					. "', `JaS_Random` = '" . mysql_real_escape_string(trim($_POST['JaS_Random']))
					. "', `JaS_speed` = '" . mysql_real_escape_string(trim($_POST['JaS_speed']))
					. "', `JaS_trigger` = '" . mysql_real_escape_string(trim($_POST['JaS_trigger']))
					. "', `JaS_pause` = '" . mysql_real_escape_string(trim($_POST['JaS_pause']))
					. "', `JaS_invert` = '" . mysql_real_escape_string(trim($_POST['JaS_invert']))
					. "', `JaS_easing` = '" . mysql_real_escape_string(trim($_POST['JaS_easing']))
					. "' where `JaS_id` = '" . $_POST['JaS_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WpJqueryAccordionSlidshowTbl." where JaS_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WpJqueryAccordionSlidshowTbl." where JaS_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $JaS_id_x = htmlspecialchars(stripslashes($data->JaS_id)); 
		if ( !empty($data) ) $JaS_Gallery_x = htmlspecialchars(stripslashes($data->JaS_Gallery)); 
		if ( !empty($data) ) $JaS_Location_x = htmlspecialchars(stripslashes($data->JaS_Location)); 
        if ( !empty($data) ) $JaS_timeout_x = htmlspecialchars(stripslashes($data->JaS_timeout));
		if ( !empty($data) ) $JaS_width_x = htmlspecialchars(stripslashes($data->JaS_width));
        if ( !empty($data) ) $JaS_height_x = htmlspecialchars(stripslashes($data->JaS_height));
		if ( !empty($data) ) $JaS_slideWidth_x = htmlspecialchars(stripslashes($data->JaS_slideWidth));
		if ( !empty($data) ) $JaS_slideHeight_x = htmlspecialchars(stripslashes($data->JaS_slideHeight));
		if ( !empty($data) ) $JaS_tabWidth_x = htmlspecialchars(stripslashes($data->JaS_tabWidth));
		if ( !empty($data) ) $JaS_Random_x = htmlspecialchars(stripslashes($data->JaS_Random));
		if ( !empty($data) ) $JaS_speed_x = htmlspecialchars(stripslashes($data->JaS_speed));
		if ( !empty($data) ) $JaS_trigger_x = htmlspecialchars(stripslashes($data->JaS_trigger));
		if ( !empty($data) ) $JaS_pause_x = htmlspecialchars(stripslashes($data->JaS_pause));
		if ( !empty($data) ) $JaS_invert_x = htmlspecialchars(stripslashes($data->JaS_invert));
		if ( !empty($data) ) $JaS_easing_x = htmlspecialchars(stripslashes($data->JaS_easing));
        $submittext = "Update Message";
    }
    ?>
  <h2>Jquery accordion slideshow</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/jquery-accordion-slideshow/setting.js"></script>
  <form name="JaS_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return JaS_submit()"  >
    <table width="100%">
      <tr>
        <td width="28%" align="left" valign="middle">Select Gallery Name:</td>
        <td width="72%" align="left" valign="middle">Time between each slide (in ms):</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><select style="width:150px;" name="JaS_Gallery" id="JaS_Gallery">
            <option value='GALLERY1' <?php if(@$JaS_Gallery_x=='GALLERY1') { echo 'selected' ; } ?>>GALLERY1 (Widget)</option>
            <option value='GALLERY2' <?php if(@$JaS_Gallery_x=='GALLERY2') { echo 'selected' ; } ?>>GALLERY2</option>
            <option value='GALLERY3' <?php if(@$JaS_Gallery_x=='GALLERY3') { echo 'selected' ; } ?>>GALLERY3</option>
            <option value='GALLERY4' <?php if(@$JaS_Gallery_x=='GALLERY4') { echo 'selected' ; } ?>>GALLERY4</option>
            <option value='GALLERY5' <?php if(@$JaS_Gallery_x=='GALLERY5') { echo 'selected' ; } ?>>GALLERY5</option>
            <option value='GALLERY6' <?php if(@$JaS_Gallery_x=='GALLERY6') { echo 'selected' ; } ?>>GALLERY6</option>
            <option value='GALLERY7' <?php if(@$JaS_Gallery_x=='GALLERY7') { echo 'selected' ; } ?>>GALLERY7</option>
            <option value='GALLERY8' <?php if(@$JaS_Gallery_x=='GALLERY8') { echo 'selected' ; } ?>>GALLERY8</option>
            <option value='GALLERY9' <?php if(@$JaS_Gallery_x=='GALLERY9') { echo 'selected' ; } ?>>GALLERY9</option>
            <option value='GALLERY10' <?php if(@$JaS_Gallery_x=='GALLERY10') { echo 'selected' ; } ?>>GALLERY10</option>
            <option value='GALLERY11' <?php if(@$JaS_Gallery_x=='GALLERY11') { echo 'selected' ; } ?>>GALLERY11</option>
            <option value='GALLERY12' <?php if(@$JaS_Gallery_x=='GALLERY12') { echo 'selected' ; } ?>>GALLERY12</option>
            <option value='GALLERY13' <?php if(@$JaS_Gallery_x=='GALLERY13') { echo 'selected' ; } ?>>GALLERY13</option>
            <option value='GALLERY14' <?php if(@$JaS_Gallery_x=='GALLERY14') { echo 'selected' ; } ?>>GALLERY14</option>
            <option value='GALLERY15' <?php if(@$JaS_Gallery_x=='GALLERY15') { echo 'selected' ; } ?>>GALLERY15</option>
            <option value='GALLERY16' <?php if(@$JaS_Gallery_x=='GALLERY16') { echo 'selected' ; } ?>>GALLERY16</option>
            <option value='GALLERY17' <?php if(@$JaS_Gallery_x=='GALLERY17') { echo 'selected' ; } ?>>GALLERY17</option>
            <option value='GALLERY18' <?php if(@$JaS_Gallery_x=='GALLERY18') { echo 'selected' ; } ?>>GALLERY18</option>
            <option value='GALLERY19' <?php if(@$JaS_Gallery_x=='GALLERY19') { echo 'selected' ; } ?>>GALLERY19</option>
            <option value='GALLERY20' <?php if(@$JaS_Gallery_x=='GALLERY20') { echo 'selected' ; } ?>>GALLERY20</option>
          </select>
        <td align="left" valign="middle"><input name="JaS_timeout" type="text" id="JaS_timeout" value="<?php echo @$JaS_timeout_x; ?>" maxlength="4" /></tr>
      <tr>
        <td align="left" valign="middle">Width of the container (in px):</td>
        <td align="left" valign="middle">Height of the container (in px):</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="JaS_width" type="text" id="JaS_width" value="<?php echo @$JaS_width_x; ?>" maxlength="4" /></td>
        <td align="left" valign="middle"><input name="JaS_height" type="text" id="JaS_height" value="<?php echo @$JaS_height_x; ?>" maxlength="4" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Width of each slide (in px):</td>
        <td align="left" valign="middle">Height of each slide (in px)</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="JaS_slideWidth" type="text" id="JaS_slideWidth" value="<?php echo @$JaS_slideWidth_x; ?>" maxlength="4" /></td>
        <td align="left" valign="middle"><input name="JaS_slideHeight" type="text" id="JaS_slideHeight" value="<?php echo @$JaS_slideHeight_x; ?>" maxlength="4" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Speed of the slide transition (in ms)</td>
        <td align="left" valign="middle">Width of each slide's &quot;tab&quot; (when clicked it opens the slide)</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="JaS_speed" type="text" id="JaS_speed" value="<?php echo @$JaS_speed_x; ?>" maxlength="4" /></td>
        <td align="left" valign="middle"><input name="JaS_tabWidth" type="text" id="JaS_tabWidth" value="<?php echo @$JaS_tabWidth_x; ?>" maxlength="4" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Pause on hover</td>
        <td align="left" valign="middle">Event type that will bind to the &quot;tab&quot; (click, mouseover, etc.)</td>
      </tr>
      <tr>
        <td align="left" valign="middle">
        <select style="width:130px;" name="JaS_pause" id="JaS_pause">
            <option value='true' <?php if(@$JaS_pause_x=='true') { echo 'selected' ; } ?>>true</option>
            <option value='false' <?php if(@$JaS_pause_x=='false') { echo 'selected' ; } ?>>false</option>
          </select></td>
        <td align="left" valign="middle">
        <select style="width:130px;" name="JaS_trigger" id="JaS_trigger">
            <option value='click' <?php if(@$JaS_trigger_x=='click') { echo 'selected' ; } ?>>click</option>
            <option value='mouseover' <?php if(@$JaS_trigger_x=='mouseover') { echo 'selected' ; } ?>>mouseover</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="left" valign="middle">Whether or not to invert the slideshow, so the last slide stays in the same position, rather than the first slide</td>
        <td align="left" valign="middle">Random :</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><select style="width:130px;" name="JaS_invert" id="JaS_invert">
        	<option value='false' <?php if(@$JaS_invert_x=='false') { echo 'selected' ; } ?>>false</option>
            <option value='true' <?php if(@$JaS_invert_x=='true') { echo 'selected' ; } ?>>true</option>
          </select></td>
        <td align="left" valign="middle"><select style="width:130px;" name="JaS_Random" id="JaS_Random">
            <option value='YES' <?php if(@$JaS_Random_x=='YES') { echo 'selected' ; } ?>>YES</option>
            <option value='NO' <?php if(@$JaS_Random_x=='NO') { echo 'selected' ; } ?>>NO</option>
          </select></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Image Folder Location</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">
        <input name="JaS_Location" type="text" id="JaS_Location" value="<?php echo @$JaS_Location_x; ?>" size="120" maxlength="1024" />
        <br /> Ex : wp-content/plugins/jquery-accordion-slideshow/gallery1/ <br />
        Note: Don't upload your original images into plug-in folder. if you upload the images into plug-in folder, you may lose the images when you update the plug-in to next version. 
        </td>
      </tr>
      <tr>
        <td height="35" colspan="3" align="left" valign="bottom"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
          <input name="publish" lang="publish" class="button-primary" onClick="JaS_redirect()" value="Cancel" type="button" />
		  <input name="Help" lang="publish" class="button-primary" onclick="JaS_help()" value="Help" type="button" /></td>
      </tr>
      <input name="JaS_id" id="JaS_id" type="hidden" value="<?php echo @$JaS_id_x; ?>">
      <input name="JaS_easing" id="JaS_easing" type="hidden" value="null">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WpJqueryAccordionSlidshowTbl." order by JaS_Gallery,JaS_id");
	if ( empty($data) ) 
	{ 
		//echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		//return;
	}
	?>
    <form name="frm_JaS_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="8%" align="left" scope="col">Gallery
              </td>
            <th width="8%" scope="col">Effect
              </td>
            <th align="left" scope="col">Image Location
              </td>
            <th width="8%" align="left" scope="col">Action
              </td>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->JaS_Gallery)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->JaS_height)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->JaS_speed)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=jquery-accordion-slideshow/jquery-accordion-slideshow.php&DID=<?php echo($data->JaS_id); ?>">Edit</a> &nbsp; <a onClick="javascript:JaS_delete('<?php echo($data->JaS_id); ?>')" href="javascript:void(0);">Delete</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
      <br />
      Check the official page for live demo and more help <a target="_blank" href="http://www.gopiplus.com/work/2011/12/21/jquery-accordion-slideshow-wordpress-plugin/">click here</a> 
    </form>
  </div>
</div>
