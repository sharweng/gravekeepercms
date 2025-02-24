<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include('../includes/config.php');
    $mode = $_GET['mode'];
    if($mode != 'user')
        include('../includes/notAdminRedirect.php');

    if(isset($_POST['submit-review'])){
        $user_id = $_POST['user_id'];
        $rev_num = $_POST['rev_num'];
        $message = $_POST['rev_msg'];

        $badWords = ['putangina', "putang ina", 'gago', 'tanga', 'ulol', 'bobo', 'lintek', 'yawa', 'pokpok', 'tarantado',
                    'inamo', 'pucha', 'putcha', 'puta', 'gagi', 'idiot', 'moron', 'stupid', 'bitch', 'ass',
                    'jerk', 'loser', 'slut', 'whore', 'asshole', 'bastard', 'fuck', 'dick', 'burat', 'bayag',
                    'inutil', 'nigger', 'nigga', 'cunt', 'dumbass', 'fucker', 'shithead', 'douchebag', 'retard', 'faggot',
                    'douche', 'jackass', 'bayot', 'pakshet', 'bwisit', 'leche', 'gaga', 'buang', 'boang', 'putragis', 'kupal',
                    'punyeta', 'shet', 'tangina', 'pakyu', 'shit', 'fucking', 'shitty'];
        $pattern = '/' . implode('|', array_map('preg_quote', $badWords)) . '/i';
        $maskedMessage = preg_replace_callback($pattern, function($matches) {
            return str_repeat('*', strlen($matches[0]));
        }, $message);

        $query = "INSERT INTO review (user_id, rev_num, rev_msg) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $user_id, $rev_num, $maskedMessage);
        $result = $stmt->execute();

        if($result){
            if($mode == 'user')
                header("Location: /gravekeepercms/review/index.php?mode=user");
            else
                header("Location: /gravekeepercms/review/");
            exit();
        }
    }

    if(isset($_POST['update-review'])){
        $rev_id = $_POST['rev_id'];
        $user_id = $_POST['user_id'];
        $rev_num = $_POST['rev_num'];
        $message = $_POST['rev_msg'];

        $badWords = ['putangina', "putang ina", 'gago', 'tanga', 'ulol', 'bobo', 'lintek', 'yawa', 'pokpok', 'tarantado',
                    'inamo', 'pucha', 'putcha', 'puta', 'gagi', 'idiot', 'moron', 'stupid', 'bitch', 'ass',
                    'jerk', 'loser', 'slut', 'whore', 'asshole', 'bastard', 'fuck', 'dick', 'burat', 'bayag',
                    'inutil', 'nigger', 'nigga', 'cunt', 'dumbass', 'fucker', 'shithead', 'douchebag', 'retard', 'faggot',
                    'douche', 'jackass', 'bayot', 'pakshet', 'bwisit', 'leche', 'gaga', 'buang', 'boang', 'putragis', 'kupal',
                    'punyeta', 'shet', 'tangina', 'pakyu', 'shit', 'fucking', 'shitty'];
        $pattern = '/' . implode('|', array_map('preg_quote', $badWords)) . '/i';
        $maskedMessage = preg_replace_callback($pattern, function($matches) {
            return str_repeat('*', strlen($matches[0]));
        }, $message);

        $query = "UPDATE review SET user_id = ?, rev_num = ?, rev_msg = ? WHERE rev_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisi", $user_id, $rev_num, $maskedMessage, $rev_id);
        $result = $stmt->execute();

        if($result){
            if($mode == 'user')
                header("Location: /gravekeepercms/review/index.php?mode=user");
            else
                header("Location: /gravekeepercms/review/");
            exit();
        }
    }
?>