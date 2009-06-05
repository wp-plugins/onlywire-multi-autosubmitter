<?php
/**
 Plugin Name: OnlyWire Multi Autosubmitter
 Plugin URI: http://lunaticstudios.com/software/wp-onlywire-multisubmit/
 Version: 1.2.4
 Description: Submits new posts to one or several OnlyWire accounts when published.
 Author: Thomas Hoefter
 Author URI: http://www.lunaticstudios.com/
 */
/*  Copyright 2009 Thomas Hoefter

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
*/
add_option('onlywire_username1','');
add_option('onlywire_password1','');
add_option('onlywire_username2','');
add_option('onlywire_password2','');
add_option('onlywire_username3','');
add_option('onlywire_password3','');
add_option('onlywire_username4','');
add_option('onlywire_password4','');
add_option('onlywire_username5','');
add_option('onlywire_password5','');
add_option('onlywire_chance','100');
add_option('onlywire_rewarded','nope');

function onlywireSubmit($post_ID){
	$chance=rand(1, 100);
	if(!get_option('onlywire_'.$post_ID)){
		if ($chance <= get_option('onlywire_chance')) {

			$post=get_post($post_ID);
			$permalink = get_permalink($post_ID);

			$account=rand(1, 5);
			
			if ($account==1) {
				if (get_option('onlywire_username1') == '') { $account++; } else {
			    $username=get_option('onlywire_username1');
			    $password=get_option('onlywire_password1');
				}
			}
			if ($account==2) {
				if (get_option('onlywire_username2') == '') { $account++; } else {
			    $username=get_option('onlywire_username2');
			    $password=get_option('onlywire_password2');
				}
			}
			if ($account==3) {
				if (get_option('onlywire_username3') == '') { $account++; } else {
			    $username=get_option('onlywire_username3');
			    $password=get_option('onlywire_password3');
				}
			}
			if ($account==4) {
				if (get_option('onlywire_username4') == '') { $account++; } else {
			    $username=get_option('onlywire_username4');
			    $password=get_option('onlywire_password4');
				}
			}
			if ($account==5) {
			    $username=get_option('onlywire_username5');
			    $password=get_option('onlywire_password5');
			}
				$ch = curl_init();
				$permalink = urlencode($permalink);
			    $url="http://www.onlywire.com/api/add?url=".$permalink."&title=".urlencode($post->post_title);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch, CURLOPT_MUTE,true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);				
				$returncode = curl_exec($ch);
				curl_close ($ch);				  				  
				  
				update_option('onlywire_accused',$account);
			    update_option('onlywire_returncode',$returncode);
			    update_option('onlywire_perm',$permalink);
			    update_option('onlywire_title',$post->post_title);
			    update_option('onlywire_url',$url);
			    update_option('onlywire_'.$post_ID,1);
			    return $post_ID;
			}
		}
	}

  
  add_action('publish_post', 'onlywireSubmit');

function add_options_page_owsubmit() {
    add_options_page('OnlyWire Submitter', 'OnlyWire Submitter', 8, 'onlywiresubmitter', 'options_page_owsubmit');
}

function options_page_owsubmit() {
	if($_POST['onlywire_save']){
		$username1 = $_POST['onlywire_username1'];
		$username2 = $_POST['onlywire_username2'];
		$username3 = $_POST['onlywire_username3'];
		$username4 = $_POST['onlywire_username4'];
		$username5 = $_POST['onlywire_username5'];
		$password1 = $_POST['onlywire_password1'];
		$password2 = $_POST['onlywire_password2'];
		$password3 = $_POST['onlywire_password3'];
		$password4 = $_POST['onlywire_password4'];
		$password5 = $_POST['onlywire_password5'];
		
		update_option('onlywire_username1',$username1);
		update_option('onlywire_password1',$password1);
		update_option('onlywire_username2',$username2);
		update_option('onlywire_password2',$password2);
		update_option('onlywire_username3',$username3);
		update_option('onlywire_password3',$password3);
		update_option('onlywire_username4',$username4);
		update_option('onlywire_password4',$password4);
		update_option('onlywire_username5',$username5);
		update_option('onlywire_password5',$password5);		
		update_option('onlywire_chance',$_POST['onlywire_chance']);
		update_option('onlywire_submitall',$_POST['onlywire_submitall']);
			
		echo '<div class="updated"><p>Options saved successfully!</p></div>';
	}
	
	if($_POST['onlywire_reward']){	
		if(get_option('onlywire_rewarded')!="true") {		
		
$urls=array(
"http://www.onlywire.com/api/add?url=http://lunaticstudios.com/software/&title=".urlencode("Useful and free Wordpress plugins"),
"http://www.onlywire.com/api/add?url=http://lunaticstudios.com/software/wp-amazon-autoposter/&title=".urlencode("Free autoposter plugin for Wordpress weblogs."),
"http://www.onlywire.com/api/add?url=http://lunaticstudios.com/software/&title=".urlencode("Free plugins for Wordpress weblogs.")
);

//"http://lunaticstudios.com/","http://lunaticstudios.com/software/","http://lunaticstudios.com/software/wp-amazon-autoposter/","http://lunaticstudios.com/software/");
//		$rurl=array("Useful and free Wordpress plugins","Free autoposter plugin for Wordpress weblogs.","Free plugins for Wordpress weblogs.","Several useful and free Wordpress plugins");

		
			for ($i = 1;  $i < 6; $i++ ) {  
			    $username=get_option("onlywire_username$i");
			    $password=get_option("onlywire_password$i");	
				if ($username != '' && $username != null && $password != '') {	
					$rand=rand(0, 2);				
					$url = $urls[$rand];
				    //$url="http://www.onlywire.com/api/add?url=http://lunaticstudios.com/software/&title=".urlencode("Useful and free Wordpress plugins");
					$ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
					curl_setopt($ch, CURLOPT_MUTE,true);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);					
					$ret = curl_exec($ch);
					curl_close ($ch);	

					if ($success != "true") {
					
					$findMich   = 'success';
					$pos = strpos($ret, $findMich);
					if ($pos === false) {
					} else {
					         $success = "true";
					}

					/*    if($ret=='<table width="70%" cellpadding="0" cellspacing="0"><tr><td>success</td></tr><table>' || $ret=="<?xml version=\"1.0\" encoding=\"utf-8\"?><result code=\"success\" />" || $ret=="<?xml version=\"1.0\" encoding=\"utf-8\"?><result code=\"success\" />" || $ret == "success" || $ret == '<result code="sucess"/>'  ){
					         $success = "true";
						}	*/					
					}
				}
			}
			
			if ($success == "true") {
				update_option('onlywire_rewarded','true');
				echo '<div class="updated"><p>Thank you, it worked!</p></div>';					
			} else {
				echo '<div class="updated"><p>Hmm, that didn\'t quite work out...</p></div>';			
			}		
		
		}  
	}

	
	?>
	<div class="wrap">
	<h2>OnlyWire Multi Autosubmitter</h2>	
	<h3>Settings</h3>
	<form method="post" id="onlywire_options">
		<p>Enter one or several OnlyWire accounts.</p>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Username #1:</td> 
				<td><input name="onlywire_username1" type="text" id="onlywire_username1" value="<?php echo get_option('onlywire_username1') ;?>"/>
			</td> 
			</tr>
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Password #1:</td> 
				<td><input name="onlywire_password1" type="password" id="onlywire_password1" value="<?php echo get_option('onlywire_password1') ;?>"/>
				</td> 
			</tr>
			
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Username #2:</td> 
				<td><input name="onlywire_username2" type="text" id="onlywire_username2" value="<?php echo get_option('onlywire_username2') ;?>"/>
			</td> 
			</tr>
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Password #2:</td> 
				<td><input name="onlywire_password2" type="password" id="onlywire_password2" value="<?php echo get_option('onlywire_password2') ;?>"/>
				</td> 
			</tr>

			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Username #3:</td> 
				<td><input name="onlywire_username3" type="text" id="onlywire_username3" value="<?php echo get_option('onlywire_username3') ;?>"/>
			</td> 
			</tr>
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Password #3:</td> 
				<td><input name="onlywire_password3" type="password" id="onlywire_password3" value="<?php echo get_option('onlywire_password3') ;?>"/>
				</td> 
			</tr>

			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Username #4:</td> 
				<td><input name="onlywire_username4" type="text" id="onlywire_username4" value="<?php echo get_option('onlywire_username4') ;?>"/>
			</td> 
			</tr>
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Password #4:</td> 
				<td><input name="onlywire_password4" type="password" id="onlywire_password4" value="<?php echo get_option('onlywire_password4') ;?>"/>
				</td> 
			</tr>

			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Username #5:</td> 
				<td><input name="onlywire_username5" type="text" id="onlywire_username5" value="<?php echo get_option('onlywire_username5') ;?>"/>
			</td> 
			</tr>
			<tr valign="top"> 
				<td width="33%" scope="row">OnlyWire Password #5:</td> 
				<td><input name="onlywire_password5" type="password" id="onlywire_password5" value="<?php echo get_option('onlywire_password5') ;?>"/>
				</td> 
			</tr>		
			<tr valign="top"> 
				<td width="33%" scope="row">Chance to submit a post:</td> 
				<td>
				<select name="onlywire_chance" id="onlywire_chance">
					<option value="100" <?php if (get_option('onlywire_chance')==100){echo "selected";}?>>100%</option>
					<option value="90" <?php if (get_option('onlywire_chance')==90){echo "selected";}?>>90%</option>
					<option value="80" <?php if (get_option('onlywire_chance')==80){echo "selected";}?>>80%</option>
					<option value="70" <?php if (get_option('onlywire_chance')==70){echo "selected";}?>>70%</option>
					<option value="60" <?php if (get_option('onlywire_chance')==60){echo "selected";}?>>60%</option>
					<option value="50" <?php if (get_option('onlywire_chance')==50){echo "selected";}?>>50%</option>
					<option value="40" <?php if (get_option('onlywire_chance')==40){echo "selected";}?>>40%</option>
					<option value="30" <?php if (get_option('onlywire_chance')==30){echo "selected";}?>>30%</option>
					<option value="20" <?php if (get_option('onlywire_chance')==20){echo "selected";}?>>20%</option>
					<option value="10" <?php if (get_option('onlywire_chance')==10){echo "selected";}?>>10%</option>
				</select>				
				</td> 
			</tr>			
		</table>
		<p class="submit"><input type="submit" name="onlywire_save" value="Save" /></p>
		</form>
		
<?php if(get_option('onlywire_rewarded')!="true"){ ?>			
	<h3>Please say Thanks!</h3>		
    <p>This plugin is free but it would be nice if you could help me out a little by pressing the button below. This will submit <a href="http://lunaticstudios.com/">my website</a> to your bookmarking accounts. Doesn't cost you anything but will make you feel better and this text disappear!</p>
<p><b>Tip:</b> You can also use this to test run your setup and see if everything works!</p>
<form method="post" id="onlywire_reward_form">
		<p class="submit"><input type="submit" name="onlywire_reward" value="Reward the Author" /></p>
	</form>		
<?php } ?>

	<h3>Information</h3>
	
       </div><div>Last post submitted to OnlyWire: <a href="<?php echo get_option('onlywire_perm'); ?>"><?php echo get_option('onlywire_title'); ?></a>.<br/>Account used: #<?php echo get_option('onlywire_accused'); ?><br/>Returncode from OnlyWire:<br/><br/></p>
<?php
$retcode=get_option('onlywire_returncode');
echo $retcode;
?>
<br/>

	   
 <h3>News</h3>
<ul style="list-style-type:disc;margin-left: 15px;">
		<?php 
		require_once(ABSPATH . WPINC . '/rss.php');
		
		$resp = _fetch_remote_file('http://www.lunaticstudios.com/news.xml');
		
		if ( is_success( $resp->status ) ) {
			$rss =  _response_to_rss( $resp );			
			$blog_posts = array_slice($rss->items, 0, 3);
			
			$posts_arr = array();
			foreach ($blog_posts as $item) {
				echo '<li><a href="'.$item['link'].'">'.$item['title'].'</a><br>'.$item['description'].'</li>';
			}
		}   ?>  
			<li><a href="http://lunaticstudios.com/software">Find my other free Wordpress plugins here.</a></li>
</ul>	   
</div>
	<?php
}
add_action('admin_menu', 'add_options_page_owsubmit');
?>