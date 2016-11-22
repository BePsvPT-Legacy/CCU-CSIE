<?php
	class roommate extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		public function add_info($room, $bed, $name, $fb_link, $line_num) {
			$name = htmlspecialchars($name, ENT_QUOTES);
			if (!preg_match("/^[1-5][1-9][0-1][0-9]$/", $room)) {
				return '寢室編號錯誤';
			} else if (!preg_match("/^[1-4]$/", $bed)) {
				return '床位編號錯誤';
			} else if (($len = strlen($name)) == 0 or $len > 16) {
				return '姓名格式錯誤';
			} else if (!preg_match("/^https:\/\/www.facebook.com\/(profile\.php\?id=[\d]+|[\w\.]+)$/i", $fb_link)) {
				return 'FB連結錯誤';
			} else if ($line_num != null and !preg_match("/^[\w]+$/", $line_num)) {
				return 'Line連結錯誤';
			} else {
				$sql = "SELECT `id` FROM `roommate` WHERE `room` = :room AND `bed` = :bed AND `semester` = :semester LIMIT 1";
				$params = array(
					':room' => $room,
					':bed' => $bed,
					':semester' => $_COOKIE['semester']
				);
				$this->query($sql, $params);
				if ($this->rowCount() == 1) {
					$this->closeCursor();
					return '床位已重複，如資料有誤，請聯繫管理員';
				} else {
					$this->closeCursor();
					$sql = "INSERT INTO `roommate` (`semester`, `room`, `bed`, `name`, `fb_link`, `line_num`, `time`, `ip`) VALUES ";
					$sql .= "(:semester, :room, :bed, :name, :fb_link, :line_num, :time, :ip)";
					$params = array(
						':semester' => $_COOKIE['semester'],
						':room' => $room,
						':bed' => $bed,
						':name' => $name,
						':fb_link' => $fb_link,
						':line_num' => $line_num,
						':time' => $this->current_time,
						':ip' => $this->ip
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						$this->closeCursor();
						return '更新資料時發生錯誤，請通報管理員';
					} else {
						$this->closeCursor();
						return true;
					}
				}
			}
		}
		
		public function search_roommate($room, $name) {
			$sql = "SELECT `id` FROM `roommate` WHERE `room` = :room AND `name` = :name AND `semester` = :semester LIMIT 1";
			$params = array(
				':room' => $room,
				':name' => $name,
				':semester' => $_COOKIE['semester']
			);
			$this->query($sql, $params);
			if ($this->rowCount() != 1) {
				$this->closeCursor();
				return array();
			} else {
				$sql = "SELECT `bed`, `name`, `fb_link`, `line_num` FROM `roommate` WHERE `room` = :room AND `semester` = :semester ORDER BY `bed` ASC";
				$params = array(
					":room" => $room,
					':semester' => $_COOKIE['semester']
				);
				$this->query($sql, $params);
				return $this->fetchAll();
			}
		}
		
		public function group_search() {
			$result = array(
				'1' => 0,
				'2' => 0,
				'3' => 0,
				'4' => 0,
				'error' => 0
			);
			$sql = "SELECT `room` FROM `roommate` WHERE `semester` = :semester GROUP BY `room` ASC";
			$params = array(
				':semester' => $_COOKIE['semester']
			);
			$this->query($sql, $params);
			$all_room = $this->fetchAll();
			$this->closeCursor();
			foreach ($all_room as $tmp) {
				$sql = "SELECT `bed` FROM `roommate` WHERE `room` = :room AND `semester` = :semester";
				$params = array(
					':room' => $tmp['room'],
					':semester' => $_COOKIE['semester']
				);
				$this->query($sql, $params);
				$temp = $this->rowCount();
				switch ($temp) {
					case 1:
						$result['1']++;
						break;
					case 2:
						$result['2']++;
						break;
					case 3:
						$result['3']++;
						break;
					case 4:
						$result['4']++;
						break;
					default :
						$result['error']++;
						break;
				}
				$this->closeCursor();
			}
			return $result;
		}
		
	}
?>