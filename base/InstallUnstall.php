<?php
namespace TestQuestionTree;

$TestQuestionTreeInstallUnstall = new TestQuestionTreeInstallUnstall(TQT_FILE);

class TestQuestionTreeInstallUnstall
{
	public $file;

	function __construct($file)
	{
		$this->file = $file;

		register_activation_hook( $this->file, [&$this, 'install'] );
		//register_uninstall_hook( $this->file, [&$this, 'unistall']);

 
		add_action( 'plugins_loaded', [&$this, 'textdomain_init'] );
	}


	function textdomain_init() {

		 $mo_file_path = TQT_PLUGIN_DIR . '/languages/'.TQT_TEXT_DOMAIN.'-'.determine_locale() . '.mo';

	     load_textdomain(TQT_TEXT_DOMAIN, $mo_file_path );
	}




	function install()
	{//Distance
	  global $wpdb;
		 $sql = "--
-- Структура таблиці `".$wpdb->prefix."qtt_answers`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."qtt_answers` (
  `idanswers` int(11) NOT NULL AUTO_INCREMENT,
  `idquestions` int(11) DEFAULT NULL,
  `answer` tinytext DEFAULT NULL,
  `nextquestion` int(11) DEFAULT NULL,
  `idattachimg` int(11) DEFAULT NULL,
  PRIMARY KEY (`idanswers`),
  KEY `fk_answers_1_idx` (`idquestions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `".$wpdb->prefix."qtt_questions`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."qtt_questions` (
  `idquestions` int(11) NOT NULL AUTO_INCREMENT,
  `idtest` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `grup` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idquestions`),
  KEY `fk_questions_1_idx` (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `".$wpdb->prefix."qtt_results`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."qtt_results` (
  `idresults` int(11) NOT NULL,
  `idtest` int(11) DEFAULT NULL,
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`result`)),
  PRIMARY KEY (`idresults`),
  KEY `fk_results_1_idx` (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `".$wpdb->prefix."qtt_tests`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."qtt_tests` (
  `idtest` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `length` int(11) NOT NULL,
  PRIMARY KEY (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `".$wpdb->prefix."qtt_answers`
--
ALTER TABLE `".$wpdb->prefix."qtt_answers`
  ADD CONSTRAINT `fk_answers_1` FOREIGN KEY (`idquestions`) REFERENCES `".$wpdb->prefix."qtt_questions` (`idquestions`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `".$wpdb->prefix."qtt_questions`
--
ALTER TABLE `".$wpdb->prefix."qtt_questions`
  ADD CONSTRAINT `fk_questions_1` FOREIGN KEY (`idtest`) REFERENCES `".$wpdb->prefix."qtt_tests` (`idtest`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `".$wpdb->prefix."qtt_results`
--
ALTER TABLE `".$wpdb->prefix."qtt_results`
  ADD CONSTRAINT `fk_results_1` FOREIGN KEY (`idtest`) REFERENCES `".$wpdb->prefix."qtt_tests` (`idtest`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
";
		 

		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );		
	}

	function unistall()
	{

		/*echo "work";
		wp_die();*/



	// global $wpdb;
	// $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'tc_price' );
	}
}


