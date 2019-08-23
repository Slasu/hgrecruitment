<?php

namespace HgQuiz;

class HgQuizPostType {

    const HgQuizTextDomain = 'hgquiz';

    private $isAdmin;
    public $HgQuizPostTypeName = 'hgquiz';
    public static $HgQuizEntriesDb = 'hgquiz_entries';

    public function __construct( bool $isAdmin )
    {
        $this->isAdmin = $isAdmin;
        $this->init();

        if( $this->isAdmin ) {
            $this->initAdmin();
        }
    }

    /**
     * init plugin settings&actions
     */
    private function init()
    {
        add_action( 'init', [ $this, 'RegisterPostVikCal' ] );
        add_action( 'wp_head', [ $this, 'RegisterAjax' ] );

        add_action( 'wp_ajax_nopriv_HgQuizFormSubmit', [ $this, 'HgQuizFormSubmit' ] );
        add_action( 'wp_ajax_HgQuizFormSubmit', [ $this, 'HgQuizFormSubmit' ] );

        wp_register_script( 'hgquiz-scripts', HgQuizUrl() . 'assets/js/hgquizScripts.js', array('jquery') );
        wp_enqueue_script( 'hgquiz-scripts' );

        wp_enqueue_style( 'hgquiz-styles', HgQuizUrl() . '/assets/css//hgquizStyle.css' );

        add_shortcode('hgquiz', [ $this, 'DisplayHgQuizSc' ] );
    }

    /**
     * Admin init
     */
    private function initAdmin()
    {
        add_action( 'add_meta_boxes', [ $this, 'RegisterHgQuizMetaBoxes' ] );
        add_action( 'save_post_hgquiz', [ $this, 'HgQuizSavePost' ] );

        wp_register_script( 'hgquiz-admin-scripts', HgQuizUrl() . 'assets/js/adminScripts.js', array('jquery') );
        wp_enqueue_script( 'hgquiz-admin-scripts' );

        if(isset($_GET['hgExportQuiz'])) {
            add_action( 'init', [ $this, 'ExportAnswers' ] );
        }
    }

    /**
     * Register HgQuiz post type
     */
    public function RegisterPostVikCal()
    {
        register_post_type( $this->HgQuizPostTypeName,
            array(
                'labels' => array(
                    'name' => __( 'HgQuiz' ),
                    'singular_name' => __( 'Quiz', self::HgQuizTextDomain ),
                    'add_new_item' => __( 'Add new Quiz item', self::HgQuizTextDomain )
                ),
                'capability_type' => 'post',
                'supports' => array( 'title' ),
                'public' => true,
                'has_archive' => false,
                'rewrite' => '',
                'menu_icon' => 'dashicons-lightbulb',
            )
        );
    }

    /**
     * Register ajaxurl
     */
    public function RegisterAjax() {
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }

    /**
     * Register HgQuiz quiz metabox
     */
    public function RegisterHgQuizMetaBoxes()
    {
        add_meta_box(
            'HgQuizQuizBox',
            __( 'Fill quiz details', self::HgQuizTextDomain ),
            [ $this, 'GenerateQuizBox' ],
            $this->HgQuizPostTypeName,
            'normal',
            'low'
        );

        add_meta_box(
            'HgQuizAnswersBox',
            __( 'Submitted answers', self::HgQuizTextDomain ),
            [ $this, 'GenerateAnswersBox' ],
            $this->HgQuizPostTypeName,
            'normal',
            'low'
        );
    }

    /**
     * Display HgQuiz quiz metabox
     */
    public function GenerateQuizBox()
    {
        global $post;
        $postData = get_post_custom( $post->ID );
        $quizData = json_decode($postData['hgQuizItem'][0]);
        //Question object would be useful
        ?>

        <div class="hgquiz--metabox">
            <label for="hgquizQuestion"><?php echo __( 'Quiz question', self::HgQuizTextDomain );?>
                <input type="text" id="hgquizQuestion" name="hgquizQuestion" value="<?php echo $quizData->question;?>" required />
            </label>

            <div id="hgquiz--metabox__answers">
                <p><?php echo __( 'Please add possible questions', self::HgQuizTextDomain );?></p>
                <button id="HgQuizAddAnswer" class="hgquiz--metabox__button button--add">
                    <?php echo __( 'Add answer', self::HgQuizTextDomain );?>
                </button>
                <ul id="HgQuizAnswersBox" class="hgquiz--metabox__answersList">
                    <?php
                    if( isset($quizData->answers) && !empty($quizData->answers) ) {
                        foreach( $quizData->answers as $answer )
                        { ?>
                            <li class="hgquiz--metabox__answer">
                                <label><?php echo __( 'Answer', self::HgQuizTextDomain );?>:
                                    <input type="text" name="hgQuizAnswer[answer][]" value="<?php echo $answer->answer;?>" />
                                </label>
                                <label><?php echo __( 'Description', self::HgQuizTextDomain );?>:
                                    <input type="text" name="hgQuizAnswer[answerDesc][]" value="<?php echo $answer->answerDesc;?>" />
                                </label>
                                <label><?php echo __( 'Is main answer?', self::HgQuizTextDomain );?>
                                    <?php $isChecked = $answer->isFinal == 'true' ? 'checked' : '';?>
                                    <input type="checkbox" name="hgQuizAnswer[isFinal][]" value="isFinal" <?php echo $isChecked;?> />
                                    <input type="hidden" name="hgQuizAnswer[isFinal][]" value="placeholder" />
                                </label>
                                <button onclick="event.preventDefault(); removeAnswer(this);" class="hgquiz--metabox__button button--remove">Remove</button>
                            </li>
                        <?php
                        }
                    } ?>
                </ul>
            </div>
        </div>

        <?php
    }

    /**
     * Display HgQuiz answers metabox
     */
    public function GenerateAnswersBox()
    {
        global $post;
        global $wpdb;
        ?>

        <div class="hgquiz--metabox">
            <?php echo __( 'Answers amount', self::HgQuizTextDomain ) . ': ';
            $tableName = $wpdb->prefix . self::$HgQuizEntriesDb;
            $answersQuery = "SELECT count(*) as cnt FROM $tableName where postId = $post->ID";
            $answers = $wpdb->get_results( $answersQuery );
            echo $answers[0]->cnt; ?>

            <?php /*<button onclick="event.preventDefault(); exportAnswers(<?php echo $post->ID;?>//);"><?php echo __( 'Export answers', self::$HgQuizEntriesDb );?></button>*/?>
            <?php $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>
            <a href="<?php echo $currentUrl;?>&hgExportQuiz=<?php echo $post->ID;?>" target="_blank"><?php echo __( 'Export answers', self::$HgQuizEntriesDb );?></a>
        </div>

        <?php
    }


    /**
     * Save HgQuiz post data
     */
    public function HgQuizSavePost()
    {
        global $post;

        if( !isset($_POST['hgquizQuestion']) || empty($_POST['hgquizQuestion']) )
            return;

        $quizQuestion = sanitize_text_field( $_POST['hgquizQuestion'] );

        $quizAnswers = [];
        if( isset($_POST['hgQuizAnswer']) && !empty($_POST['hgQuizAnswer']) )
        {
            foreach($_POST['hgQuizAnswer']['answer'] as $k => $answer )
            {
                $quizAnswers[$k]['answer'] = sanitize_text_field($answer);
            }

            foreach($_POST['hgQuizAnswer']['answerDesc'] as $k => $answerDesc )
            {
                $quizAnswers[$k]['answerDesc'] = sanitize_text_field($answerDesc);
            }

//            $answersAmount = count( $_POST['hgQuizAnswer']['answer'] );
            $finalAnswersArrayCount = count( $_POST['hgQuizAnswer']['isFinal'] );

            $answerNo = 0;
            for( $i = 0; $i < $finalAnswersArrayCount; $i++ )
            {
                if( $_POST['hgQuizAnswer']['isFinal'][$i] == 'isFinal' )
                {
                    $quizAnswers[$answerNo]['isFinal'] = 'true';
                    $i++;
                } else {
                    $quizAnswers[$answerNo]['isFinal'] = 'false';
                }

                $answerNo++;
            }
        }

        $quizData = [
            'question' => $quizQuestion,
            'answers' => $quizAnswers
        ];

        update_post_meta( $post->ID, "hgQuizItem", json_encode($quizData, JSON_UNESCAPED_UNICODE) );
    }

    public function DisplayHgQuizSc($atts)
    {
        $shortcodeAtts = shortcode_atts( ['id' => ''], $atts );
        $quizId = $shortcodeAtts['id'];
        $quizCustom = get_post_custom( $quizId );
        $quizData = json_decode( $quizCustom['hgQuizItem'][0] );

        ob_start();
        $this->displayHgQuizForm($quizData, $quizId);
        $output = ob_get_clean();

        return $output;
    }

    private function displayHgQuizForm($quizData, $quizId)
    {
        if( isset($_COOKIE['HgQuizSubmittedForm'.$quizId]) && $_COOKIE['HgQuizSubmittedForm'.$quizId] == 'submitted' ) {
            echo '<h2>' . __('You have already answered to this question!', self::HgQuizTextDomain) . '</h2>';
            return;
        }
        ?>
        <div class="hgquiz--holder">
            <h2><?php echo $quizData->question;?></h2>
            <div id="HgQuizAjaxLoader" class="hgquiz--ajaxloader">
                <img src="<?php echo HgQuizUrl();?>/assets/images/ajax-loader.gif" alt="Ajax loader" />
            </div>
            <form id="HgQuizForm" class="hgquiz--form" method="POST" action="#" data-quiz-id="<?php echo $quizId;?>">
                <ul class="hgquiz--questions">
                    <?php foreach( $quizData->answers as $k => $answer )
                    {
                        $isFinal = $answer->isFinal == 'true'
                            ? ' data-is-final="true"'
                            : '';
                        ?>
                        <li class="hgquiz--question__single"<?php echo $isFinal;?>>
                            <div class="hgquiz--question__checkboxMark">
                                <input type="checkbox" name="answerNo<?php echo $k;?>" id="answerNo<?php echo $k;?>" value="<?php echo $k;?>"/>
                            </div>
                            <label for="answerNo<?php echo $k;?>">
                                <span><?php echo $answer->answer;?></span>
                                <span><?php echo $answer->answerDesc;?></span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>

                <div class="hgquiz--submit__holder">
                    <input type="submit" id="HgQuizFormSubmit" class="hgquiz--submit" value="<?php echo __( 'Abschicken', self::HgQuizTextDomain );?>">
                </div>
                <div id="HgQuizErrMsg"></div>
            </form>
            <div id="HgQuizSuccessMsg"></div>
        </div>
        <?php
    }

    public function HgQuizFormSubmit()
    {
        if( !isset($_POST['quizId']) && empty($_POST['quizId']) ) {
            echo json_encode(['status' => 'error', 'msg' => 'Missing quiz id!']);
        }

        $quizId = $_POST['quizId'];
        $postData = explode('&', $_POST['data']);
        $usersAnswers = [];

        foreach($postData as $data)
        {
            $answer = explode('=', $data)[1];
            array_push($usersAnswers, $answer);
        }

        $postData = get_post_custom($quizId);
        $quizData = json_decode($postData['hgQuizItem'][0]);
        $quizQuestion = $quizData->question;

        $answerContent = [];
        foreach( $usersAnswers as $answer )
        {
            $answerCombined = $quizData->answers[$answer]->answer . ' ' . $quizData->answers[$answer]->answerDesc;
            array_push($answerContent, $answerCombined );
        }

        global $wpdb;
        $result = $wpdb->insert(
            $wpdb->prefix . self::$HgQuizEntriesDb,
            array(
                'postId' => (int) $quizId,
                'entryQuestion' => $quizQuestion,
                'entryAnswers' => json_encode( $answerContent, JSON_UNESCAPED_UNICODE ),
                'entryEmail' => '',
            )
        );

        if($result !== false) {
            echo json_encode(['status' => 'success', 'msg' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Something went wrong']);
        }

        die();
    }

    public function ExportAnswers()
    {
        global $wpdb;

        $quizId = $_GET['post'];
        $tableName = $wpdb->prefix . self::$HgQuizEntriesDb;
        $answersQuery = "SELECT entryQuestion, entryAnswers FROM $tableName where postId = $quizId";
        $answersResult = $wpdb->get_results( $answersQuery );
        $csvRow = "id,question,answers\n";

        foreach($answersResult as $k => $row)
        {
            $answersObj = json_decode($row->entryAnswers);
            $answers = '';

            foreach($answersObj as $answer) {
                $answers .= $answer;
            }

            $csvRow .= "'$k','$row->entryQuestion','$answers'\n";
        }

        $datetime = date('Ymdhis');
        $filename = 'HgQuiz_'.$quizId.'_'.$datetime;

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $csvRow;

        exit();
    }
}