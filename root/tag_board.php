<?php
/**
*
*	@modname	TAG BOARD 4 phpBB3
*	@version	3.0.4.2812
*	@license	GNU Public License
*	@author		bx67212
*
**/

/**
* @ignore
*/
define('IN_PHPBB',true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'),1);
include($phpbb_root_path . 'common.'.$phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

// Tb version
$tb_version = '3.0.4.2812';

// Get some variables
$tb_bsw 		= (int) $config['tb_bsw'];
$tb_auth 		= (int) $config['tb_auth'];
$tb_purge 		= (int) $config['tb_purge'];
$tb_limit 		= (int) $config['tb_limit'];
$tb_history		= (int) $config['tb_history'];
$tb_buttons		= (int) $config['tb_buttons'];
$tb_refresh		= (int) $config['tb_refresh'];
$tb_user_id 	= (int) $user->data['user_id'];
$tb_maxlength 	= (int) $config['tb_maxlength'];

// Grab data
$tpi 	= (int) request_var('id', '');
$mode 	= (string) request_var('mode', '');
$action = (string) request_var('action', '');
$submit = (isset($_POST['submit'])) ? true : false;

// Purge old tags
if($tb_purge){
	$dif = (int) (time() - ($tb_purge * 86400));
	$sql = 'DELETE FROM ' . TB_TABLE . "
			WHERE tb_post_time < $dif";
	$db->sql_query($sql);
}

// Return var string for user id
function get_user_id_var($mode,$u){
	global $db;
		$sql = 'SELECT *
				FROM ' . USERS_TABLE . "
				WHERE user_id = $u";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
	return $row[$mode];
}

// Is author
function is_author($u){
	global $db, $tpi;
		$sql = 'SELECT *
				FROM ' . TB_TABLE . "
				WHERE tb_post_id = $tpi";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
	return ($u == $row['tb_poster_id']) ? true : false;
}

switch($mode){
	case 'controlpanel':
		switch($action){
			case 'delete':
				if($auth->acl_get('m_') || $auth->acl_get('a_') || is_author($tb_user_id)){
					if(!$submit){
						confirm_box(false, $user->lang['CONFIRM_OPERATION'], '', 'tag_board_confirm.html');
					}
					else{
						$sql = 'DELETE FROM ' . TB_TABLE . "
								WHERE tb_post_id = $tpi";
						$db->sql_query($sql);
						header("Location: ".append_sid("{$phpbb_root_path}tag_board.php"));
					}
				}
			break;

			case 'edit':
				if($auth->acl_get('m_') || $auth->acl_get('a_') || is_author($tb_user_id)){
					if(!$submit){
						$sql = 'SELECT *
								FROM ' . TB_TABLE . "
								WHERE tb_post_id = $tpi";
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$message = generate_text_for_edit($row['tb_post_text'],$row['tb_bbcode_uid'],$row['tb_flags']);
						$hidden = '<input type="hidden" name="allow_bbcode" value="'.$message['allow_bbcode'].'"><input type="hidden" name="allow_urls" value="'.$message['allow_urls'].'"><input type="hidden" name="allow_smilies" value="'.$message['allow_smilies'].'">';
						confirm_box(false, $message['text'], $hidden, 'tag_board_edit.html');
					}
					else{
						$message = utf8_normalize_nfc(request_var('tb_message', '', true));
						if($message){
							$allow_urls 	= (int) request_var('allow_urls', '0');
							$allow_bbcode 	= (int) request_var('allow_bbcode', '0');
							$allow_smilies 	= (int) request_var('allow_smilies', '0');
							generate_text_for_storage($message,$uid,$bitfield,$flags,$allow_bbcode,$allow_urls,$allow_smilies);
							$sql_ary = array(
								'tb_post_text' 			=> (string) $message,
								'tb_bbcode_bitfield' 	=> (string) $bitfield,
								'tb_bbcode_uid' 		=> (string) $uid,
								'tb_flags' 				=> (int) $flags,
							);
							$sql = 'UPDATE ' . TB_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . ' WHERE tb_post_id = ' . $tpi;
							$db->sql_query($sql); 
						}
						header("Location: ".append_sid("{$phpbb_root_path}tag_board.php"));
					}
				}
			break;

			default:
				if($auth->acl_get('a_')){
					if(!$submit){
						$template->assign_vars(array(
							'VERSION' 	=> $tb_version . ' ( <a href="http://bx67212.netsons.org/viewforum.php?f=2" target="_blank">check 4 update</a> )',
							'AUTH1' 	=> ($tb_auth == "1") ? 'checked' : null,
							'AUTH2' 	=> ($tb_auth == "2") ? 'checked' : null,
							'AUTH3' 	=> ($tb_auth == "3") ? 'checked' : null,
							'LIMIT' 	=> $tb_limit,
							'MAXLENGTH' => $tb_maxlength,
							'BUTTONS1' 	=> ($tb_buttons) ? 'checked' : null,
							'BUTTONS2' 	=> (!$tb_buttons) ? 'checked' : null,
							'BSW1' 		=> ($tb_bsw) ? 'checked' : null,
							'BSW2' 		=> (!$tb_bsw) ? 'checked' : null,
							'REFRESH' 	=> $tb_refresh,
							'PURGE' 	=> $tb_purge,
							'HISTORY' 	=> $tb_history,
						));
						// Output the page
						page_header();
						$template->set_filenames(array('body' => 'tag_board_admin.html'));
						page_footer();
					}
					else{
						set_config('tb_auth', (int) request_var('tb_auth', '1'));
						set_config('tb_limit', (int) request_var('tb_limit', '20'));
						set_config('tb_maxlength', (int) request_var('tb_maxlength', '500'));
						set_config('tb_buttons', (int) request_var('tb_buttons', '1'));
						set_config('tb_bsw', (int) request_var('tb_bsw', '1'));
						set_config('tb_refresh', (int) request_var('tb_refresh', '0'));
						set_config('tb_purge', (int) request_var('tb_purge', '0'));
						set_config('tb_history', (int) request_var('tb_history', '0'));
						((int) request_var('tb_deleteall', '0')) ? $db->sql_query('TRUNCATE TABLE ' . TB_TABLE) : null;
						header("Location: ".append_sid("{$phpbb_root_path}tag_board.php"));
					}
				}
				else{
					header("Location: ".append_sid("{$phpbb_root_path}tag_board.php"));
				}
			break;
		}
	break;

	case 'write':
		if($tb_auth == "2" || $tb_user_id != "1"){
			$message = utf8_normalize_nfc(request_var('tb_message', '', true));
			if($message){
				$allow_urls 	= ((int) request_var('w', '0')) ? false : true;
				$allow_bbcode 	= ((int) request_var('b', '0')) ? false : true;
				$allow_smilies 	= ((int) request_var('s', '0')) ? false : true;
				generate_text_for_storage($message,$uid,$bitfield,$flags,$allow_bbcode,$allow_urls,$allow_smilies);
				$sql_ary = array(
					'tb_poster_id' 			=> (int) $tb_user_id,
					'tb_post_time' 			=> (int) time(),
					'tb_post_username' 		=> $tb_user_id != "1" ? (string) $user->data['username'] : (string) $user->ip,
					'tb_post_text' 			=> (string) $message,
					'tb_bbcode_bitfield' 	=> (string) $bitfield,
					'tb_bbcode_uid' 		=> (string) $uid,
					'tb_flags' 				=> (int) $flags,
				);
				$sql = 'INSERT INTO ' . TB_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);                           
				$db->sql_query($sql);
				// Store only $tb_history tags
				if($tb_history){
					$sql = 'SELECT *
							FROM ' . TB_TABLE . "
							ORDER BY tb_post_id DESC";
					$result = $db->sql_query($sql);
					$i = 1;
					$flag = 0;
					while($row = $db->sql_fetchrow($result) AND $flag == 0){
						$pid = $row['tb_post_id'];
						if($i == $tb_history){
							$flag = 1;
							$sql = 'DELETE
									FROM ' . TB_TABLE . "
									WHERE tb_post_id < $pid";
							$db->sql_query($sql);
						}
						$i++;
					}
				}		
			}
			header("Location: ".append_sid("{$phpbb_root_path}tag_board.php"));
		}
	break;

	default:
		if($tb_auth == "1" || $tb_auth == "2" || $tb_user_id != "1"){
			if(!$mode){
				$template->assign_vars(array('REFRESH' => $tb_refresh));
				$limit = "LIMIT $tb_limit";
			}
			else {
				$limit = null;
			}
			$sql = 'SELECT *
					FROM ' . TB_TABLE . "
					ORDER BY tb_post_time DESC " .
					$limit;
			$result = $db->sql_query($sql);
			$i = "0";
			while($row = $db->sql_fetchrow($result)){
				$message = generate_text_for_display($row['tb_post_text'],$row['tb_bbcode_uid'],$row['tb_bbcode_bitfield'],$row['tb_flags']);
				$message = str_replace('class="postlink"','target="_blank"',$message);
				$message = str_replace('class="postlink-local"','target="_top"',$message);
				$template->assign_block_vars('tb_postrow',array(
					'STYLE' 		=> ($i % 2) == 0 ? "style1" : "style2", 
					'POST_TIME' 	=> $user->format_date($row['tb_post_time']),
					'USER_COLOUR' 	=> get_user_id_var('user_colour',$row['tb_poster_id']),
					'POST_USERNAME' => $row['tb_post_username'],
					'POST_TEXT' 	=> $message,
					'S_OPTIONS' 	=> ($auth->acl_get('m_') || $auth->acl_get('a_') || ($tb_user_id == $row['tb_poster_id']) && $tb_user_id != "1") ? "1" : null,
					'U_EDIT' 		=> append_sid("{$phpbb_root_path}tag_board.php", 'mode=controlpanel&amp;action=edit&amp;id='.$row['tb_post_id']),
					'U_DELETE' 		=> append_sid("{$phpbb_root_path}tag_board.php", 'mode=controlpanel&amp;action=delete&amp;id='.$row['tb_post_id']),
					'U_PROFILE' 	=> $row['tb_poster_id'] != "1" ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u='.$row['tb_poster_id']) : null,
				));
				$i++;
			}
			// Output the page
			page_header();
			$template->set_filenames(array('body' => 'tag_board_layout.html'));
			page_footer();
		}
	break;
}
?>