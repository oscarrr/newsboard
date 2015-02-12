<?php
/**
 * The Class that builds the ticker nest values for the template file
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpRender 
{
    //Array with all values for the template file nests
    public $tA = array();
    
    /**
     * Class Construct. Var assigning.
     * @param Array $db_array - All values from the DB (/custom/ themes and settings)
     * @param String $plugin_dir - Plugin dir
     */
    public function __construct($db_array, $plugin_dir, $currentVersion)
    {
        $this->tA = $db_array;
        $this->tA['plugin_dir'] = $plugin_dir;
        $this->tA['margin_bottom_holder'] = 1;
        $this->tA['error_handle'] = "";
        $this->tA['show_arrows'] = true;  
        $this->tA['number_of_news'] = 10;
        $this->tA['nbp_version'] = $currentVersion;
        $this->tA['loader_path'] = $plugin_dir . 'images/loader.gif';
    }
    
    /**
     * Converts data to appropriate HTML
     */
    public function doTheMagic()
    {        
        if($this->tA['feed'] == 'categories')
            $this->createContent();
        elseif($this->tA['feed'] == 'rss')
            $this->createRSSContent();
        
        $this->luxuryTouchAdjustment();
        $this->finalAdjustments();
    }
    
    /**
     * Builds appropriate CSS3 for luxury effects
     */
    private function luxuryTouchAdjustment()
    {
        if($this->tA['luxury_touch'] == 'checked="checked"')
        {
            $this->tA['bg_luxury_touch'] = 'background-image: linear-gradient(270deg, #888 0%, #EEE 1px, #D2D2D2 1px, #C8C8C8 50%, #D5D5D5 50%, #C5C5C5 100%);
background-image: -webkit-linear-gradient(270deg, #888 0%, #EEE 1px, #D2D2D2 1px, #C8C8C8 50%, #D5D5D5 50%, #C5C5C5 100%);
background-image: -moz-linear-gradient(270deg, #888 0%, #EEE 1px, #D2D2D2 1px, #C8C8C8 50%, #D5D5D5 50%, #C5C5C5 100%);
background-image: -o-linear-gradient(270deg, #888 0%, #EEE 1px, #D2D2D2 1px, #C8C8C8 50%, #D5D5D5 50%, #C5C5C5 100%);
background-image: -ms-linear-gradient(270deg, #888 0%, #EEE 1px, #D2D2D2 1px, #C8C8C8 50%, #D5D5D5 50%, #C5C5C5 100%);';
            
            $this->tA['bg_over_luxury_touch'] = 'background-image: linear-gradient(270deg, #979797 0%, #FDFDFD 1px, #E1E1E1 1px, #D7D7D7 50%, #E4E4E4 50%, #D4D4D4 100%);
background-image: -webkit-linear-gradient(270deg, #979797 0%, #FDFDFD 1px, #E1E1E1 1px, #D7D7D7 50%, #E4E4E4 50%, #D4D4D4 100%);
background-image: -moz-linear-gradient(270deg, #979797 0%, #FDFDFD 1px, #E1E1E1 1px, #D7D7D7 50%, #E4E4E4 50%, #D4D4D4 100%);
background-image: -o-linear-gradient(270deg, #979797 0%, #FDFDFD 1px, #E1E1E1 1px, #D7D7D7 50%, #E4E4E4 50%, #D4D4D4 100%);
background-image: -ms-linear-gradient(270deg, #979797 0%, #FDFDFD 1px, #E1E1E1 1px, #D7D7D7 50%, #E4E4E4 50%, #D4D4D4 100%);';
            
            $this->tA['bar_luxury_touch'] = 'background-image: linear-gradient(270deg, #222 0%, #888 1px, #6C6C6C 1px, #626262 50%, #6F6F6F 50%, #5F5F5F 100%);
background-image: -webkit-linear-gradient(270deg, #222 0%, #888 1px, #6C6C6C 1px, #626262 50%, #6F6F6F 50%, #5F5F5F 100%);
background-image: -moz-linear-gradient(270deg, #222 0%, #888 1px, #6C6C6C 1px, #626262 50%, #6F6F6F 50%, #5F5F5F 100%);
background-image: -o-linear-gradient(270deg, #222 0%, #888 1px, #6C6C6C 1px, #626262 50%, #6F6F6F 50%, #5F5F5F 100%);
background-image: -ms-linear-gradient(270deg, #222 0%, #888 1px, #6C6C6C 1px, #626262 50%, #6F6F6F 50%, #5F5F5F 100%);';
        }
    }
    
    /**
     * Final CSS and JavaScript calculations
     * @global Array $nbp_transition_types - Settings: Transition types
     */
    private function finalAdjustments()
    {
        global $nbp_transition_types;
        if(!is_array($nbp_transition_types))
            return;
            
        $this->tA['news_fit'] = ($this->tA['new_height'] * $this->tA['board_news_fit'])+($this->tA['board_news_fit'])*$this->tA['margin_bottom_holder']-$this->tA['margin_bottom_holder'];
        $this->tA['height_slider'] = ($this->tA['new_height'] * $this->tA['actual_news_number'])+($this->tA['actual_news_number'])*$this->tA['margin_bottom_holder']+($this->tA['new_height'] + $this->tA['margin_bottom_holder'])*$this->tA['invisible_news'];
        $this->tA['height_slider'] = $this->tA['height_slider'] - $this->tA['margin_bottom_holder'];
        $this->tA['all_height'] = $this->tA['news_fit'] + $this->tA['bar_height']+1;
        if($this->tA['auto_scroll'] == 'checked="checked"')
            $this->tA['auto_scroll'] = 'true';
        else
            $this->tA['auto_scroll'] = 'false';
        $this->tA['transition_type'] = $nbp_transition_types[$this->tA['transition_type']];
        if($this->tA['number_of_news'] > $this->tA['actual_news_number'])
            $this->tA['number_of_news'] = $this->tA['actual_news_number'];
    }
    
    /**
     * Detects Old Browsers.
     * @return Boolean - If match our requirements
     */
    private function detectOldBrowsers()
    {   
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        if(preg_match('/(chromium)[ \/]([\w.]+)/', $ua))
            $browser = 'chromium';
        else if(preg_match('/(chrome)[ \/]([\w.]+)/', $ua))
            $browser = 'chrome';
        else if(preg_match('/(safari)[ \/]([\w.]+)/', $ua))
            $browser = 'safari';
        else if(preg_match('/(opera)[ \/]([\w.]+)/', $ua))
                $browser = 'opera';
        else if(preg_match('/(msie)[ \/]([\w.]+)/', $ua))
            $browser = 'msie';
        else if(preg_match('/(mozilla)[ \/]([\w.]+)/', $ua))
            $browser = 'mozilla';
    
        preg_match('/('.$browser.')[ \/]([\w]+)/', $ua, $version);
    
        $browser_details = array('name'=>$browser,'version'=>$version[2]);
        
        if( ($browser_details['name'] == 'msie' && $browser_details['version'] < 9) || ($browser_details['name'] == 'mozilla' && $browser_details['version'] < 4) || ($browser_details['name'] == 'safari' && $browser_details['version'] < 5))
            return true;
        else
            return false;
    }
    
    /**
     * Calculates new image size for the ticker.
     * @param Integer $width - Image width
     * @param Integer $height - Image height
     * @param Integer $min_width - Minimal image width
     * @param Integer $min_height - Minimal image height
     * @return Array - New image size
     */
    private function resizeThumb($width, $height, $min_width, $min_height)
    {
        $pic_ratio = $width / $height;
        $box_ratio = $min_width / $min_height;
        
        if($box_ratio >= $pic_ratio)
        {
           $ratio = $width / $min_width;
           $new_width = $min_width;
           $new_height = intval($height / $ratio); 
        }
        else
        {
           $ratio = $height / $min_height;
           $new_width = intval($width / $ratio);
           $new_height = $min_height; 
        }
        
        return array($new_width, $new_height);
    }
    
    /**
     * Cuts given text to a given number of words.
     * @param String $string - The text
     * @param Integer $limit - The number of words
     * @param String $dots - Read More string
     * @return String - Cutted text
     */
    private function wordsCut($string, $limit, $dots)
    {
        $origString = $string;
        $words=explode(' ', $string);
        if(count($words) > $limit)
        {
            array_splice($words, $limit);
            $string = implode(' ', $words);
        }
        if(count($words)<$limit)
            $haveDots = "";
        else
            $haveDots = $dots;
        return trim($string).$haveDots;
    }
    
    /**
     * Cuts given text to a given number of symbols.
     * @param String $string - The text
     * @param Integer $limit - The number of symbols
     * @param String $dots - Read more string
     * @return String - Cutted text
     */
    private function symbolsCut( $string, $limit, $dots )
    {
        $string = htmlspecialchars_decode( $string );
        
        if ( function_exists( 'mb_strlen' ) )
            $length = mb_strlen( $string, 'UTF-8' );
        else
            $length = strlen( $string );
            
        if ( $length > $limit ) 
        {   
            if ( function_exists( 'mb_substr' ) )
                $subex = mb_substr( $string, 0, $limit+1, 'UTF-8' );
            else
                $subex = substr( $string, 0, $limit+1 );
            
            if ( function_exists( 'mb_substr' ) )
                $word_cut = mb_substr( $subex, $limit, $limit+1, 'UTF-8' ) != " ";
            else
                $word_cut = substr( $subex, $limit, $limit+1 ) != " ";  
            
            if( $word_cut )
            {
                $exwords = explode(' ', $subex);
                $index_last_word = count($exwords) - 1;
                
                if ( function_exists( 'mb_strlen' ) )
                    $last_word_length = mb_strlen( $exwords[$index_last_word], 'UTF-8' );
                else
                    $last_word_length = strlen( $exwords[$index_last_word] );
                
                if ( function_exists( 'mb_substr' ) )    
                    $subex = mb_substr($subex, 0, $limit - $last_word_length, 'UTF-8');
                else
                    $subex = substr($subex, 0, $limit - $last_word_length);
            } 
            return $subex . $dots;
        } 
        else
            return $string;
    }
    
    /**
     * Defines custom excerpt length.
     * @return Integer - Number of symbols/words
     */
    public function customExcerptLength() 
    {
        return $this->tA['text_cut_after'];
    }
    
    /**
     * Defines custom excerpt Read More string.
     * @return String - The Desired string
     */
    public function newExcerptMore() 
    {
        return "<span class=\"read_more\">&nbsp;<a href=\"#\">" . $this->tA['read_more_string'] . "</a></span>";
    }
    
    /**
     * Creates ticker HTML from tA array with the saved values.
     * @uses get_posts() - WP function that retrieves a list of latest posts or posts matching criteria
     * @uses setup_postdata() - Helps to format custom query results for using Template tags
     * @depends has_post_thumbnail() - Checks if a post has a Post Thumbnail attached
     * @depends wp_get_attachment_image_src() - Returns an array with the image attributes "url", "width" and "height", of an image attachment file
     * @depends get_post_thumbnail_id() - Returns the ID of the Thumbnail attached to the post
     */
    private function createContent()
    {   
        global $wpdb;
        $count_iter = 0;
        
        $terms = $wpdb->get_results( $wpdb->prepare( "SELECT GROUP_CONCAT( t.term_id SEPARATOR ',' ) as ids, tt.taxonomy FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE t.term_id IN (" . implode(',', $this->tA['pickcategories'])) . ") GROUP BY tt.taxonomy" );
        $tax_query = array('relation' => 'OR');
        
        foreach ( $terms as $term )
        {
            array_push( $tax_query, array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'id',
                    'terms' => explode(',', $term->ids)
                ));
        }
        
        $args = array('numberposts' => $this->tA['number_of_news'], 'tax_query' => $tax_query, 'post_status'  => 'publish', 'orderby' => $this->tA['order_type'], 'order' => $this->tA['order'], 'post_type' => get_post_types());
        
        $lastposts = get_posts($args);
        
        if($lastposts == null)
            $this->tA['error_handle'] = "<div class=\"newsboard_plugin_error_handle\">NewsBoard: No News available!</div>";
        else
        {
            $titleCutMethod = $this->tA['title_cutting_rule'] . 'Cut';
            $textCutMethod = $this->tA['text_cutting_rule'] . 'Cut';
            foreach($lastposts as $post) : setup_postdata($post);
                $post_title_cut = $this->$titleCutMethod($post->post_title, $this->tA['title_cut_after'], "...");
                if(is_callable('has_post_thumbnail') && has_post_thumbnail($post->ID) && $this->tA['show_thumbnails'] == 'checked="checked"')
                {
                    $image_path = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array($this->tA['thumbnail_width'],$this->tA['thumbnail_height']));
                    $changedImgSize = $this->resizeThumb($image_path[1], $image_path[2], $this->tA['thumbnail_width'], $this->tA['thumbnail_height']);
                    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array($changedImgSize[0],$changedImgSize[1]));
                       
                    if($this->tA['image_border_on'] == 'checked="checked"')
                        $flag_border = 1;
                    else
                        $flag_border = 0;
                    $img_padding = intval($this->tA['thumbnail_width'] * 0.1);
                    
                    $imgTop = ($changedImgSize[1] - $this->tA['thumbnail_height'])/-2;
                    $imgLeft = ($changedImgSize[0] - $this->tA['thumbnail_width'])/-2;
                    $image = "<td class=\"image\" style=\"width: 1%; padding-left: " . $img_padding . "px; padding-right: " . $img_padding . "px; \">
	                        <div class=\"new_thumbnail\" style=\"width:" . $this->tA['thumbnail_width']. "px; height:" . $this->tA['thumbnail_height'] . "px; border:" . $this->tA['image_border'] . ";\"><img class=\"nbp_real_img\" style=\"width:" . $image_path[1] . "px; height:" . $image_path[2] . "px; top:" . $imgTop . "px; left:" . $imgLeft . "px;\" data-src=\"" . $image_url[0] . "\" alt=\"" . $post_title_cut . "\" /></div>
	                     </td>";
                        
                    $padding_right = max(max($this->tA['roundness_top_right'], $this->tA['roundness_bottom_right'])/2, 10);
                    $content_style = "style=\"padding: 0px " . $padding_right . "px 0px 0px;\"";
                }
                else
                {
                    $image = "";
                    $padding_left = max(max($this->tA['roundness_top_left'], $this->tA['roundness_bottom_left'])/2, 10);
                    $padding_right = max(max($this->tA['roundness_top_right'], $this->tA['roundness_bottom_right'])/2, 10);
                    $content_style = "style=\"width:100%; padding: 0px " . $padding_right . "px 0px " . $padding_left . "px;\"";
                }
                if($this->tA['show_date'] == 'checked="checked"')
                    $show_date = "<div class=\"new_date\">" . mysql2date(__($this->tA['date_format_string']), $post->post_date, true) . "</div>";
                else
                    $show_date = '';
                if($this->tA['show_text'] == 'checked="checked"')
                {
                    add_filter('excerpt_length', array($this, 'customExcerptLength'), 999);
                    add_filter('excerpt_more', array($this, 'newExcerptMore'), 999);
                
                    if($this->tA['text_from'] == 'text')
                    {                
                        $content = get_the_excerpt();
                        if($textCutMethod == 'symbolsCut')
                            $content = $this->symbolsCut($content, $this->tA['text_cut_after'], $this->newExcerptMore());
                        $show_text = "<div class=\"new_text\">" . $content . "</div>";
                    }
                    elseif($this->tA['text_from'] == 'excerpt')
                    {
                        if($post->post_excerpt == '')
                            $excerpt = get_the_excerpt();
                        else
                            $excerpt = $post->post_excerpt;
                        if($textCutMethod == 'symbolsCut')
                                $excerpt = $this->symbolsCut($excerpt, $this->tA['text_cut_after'], $this->newExcerptMore());
                        $show_text = "<div class=\"new_text\">" . $excerpt . "</div>";                   
                    }

                    remove_filter('excerpt_length', array($this, 'customExcerptLength'), 999);
                    remove_filter('excerpt_more', array($this, 'newExcerptMore'), 999);
                                              
                }
                
                $contentTemp[$count_iter] = 
                "<a class=\"new_holder\" title=\"" . $post_title_cut . "\" href=\"" . get_permalink($post->ID) . "\"" . ($this->tA['open_links_in'] == "blank" ? " target=\"_blank\"" : "") . ">
                    <table class=\"new_table\">
                        <tr>
                            " . $image . "
                            <td class=\"content\" " . $content_style . ">
                                <div class=\"content_holder\">
                                    <div class=\"new_title\">" . $post_title_cut . "</div>
                                    " . $show_date . "
                                    " . $show_text . "
                                </div>
                            </td>
                        </tr>
                    </table>
                </a>";
                $count_iter++;
                $cutted_title = "";
            endforeach;
        }
        $this->tA['actual_news_number'] = $count_iter; 
        $this->tA['invisible_news_top'] = 0;
        if($this->tA['board_news_fit'] >= $this->tA['actual_news_number'])
        {
            $this->tA['board_news_fit'] = $this->tA['actual_news_number'];
            $this->tA['show_arrows'] = false;
            for($i=0; $i<$count_iter; $i++)
                $this->tA['render_content'] .= $contentTemp[$i];
        }
        else
        {
            if($this->tA['actual_news_number'] < 4)
            {
                $invisibleUp = $contentTemp[$count_iter-2] . $contentTemp[$count_iter-1];
                $invisibleDown = $contentTemp[0] . $contentTemp[1];
                $this->tA['invisible_news'] = 4;
                $this->tA['invisible_news_top'] = 2;
            }
            else
            {
                for($k=0; $k<intval($count_iter/2); $k++)
                    $invisibleDown .= $contentTemp[$k];
                for($k=intval($count_iter/2); $k<$count_iter; $k++)
                {
                    $invisibleUp .= $contentTemp[$k];
                    $this->tA['invisible_news_top']++;
                } 
                $this->tA['invisible_news'] = $count_iter;
            }
            for($i=0; $i<$count_iter; $i++)
                $this->tA['render_content'] .= $contentTemp[$i];
            $this->tA['render_content'] = $invisibleUp . $this->tA['render_content'] . $invisibleDown;
        }
        $this->makeBar();
    }
    
    /**
    * Finds image sorce in text. 
    * @param String $text - The text
    * @param String $encoding - The encoding
    * @return String - The image source
    */
    private function findImageInText($text, $encoding)
    {
        $text = html_entity_decode($text, ENT_QUOTES, $encoding);
        $pattern = "/<img[^>]+\>/iu";
        preg_match($pattern, $text, $matches);
        $link = '';
        
        if(count($matches) > 0)
        {
            $pattern1 = '/src=[\'"]?([^\'" >]+)[\'" >]/';     
            preg_match($pattern1, $matches[0], $link);
            $link = $link[1];
            $link = urldecode($link);
        }
        
        return $link;
    }
    
    /**
     * RSS Parser. 
     */
    private function rssParse()
    {
        error_reporting(0);
        $articles = array();
        
        //set caching to 10 minutes
        add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', 'return 600;') );
        
        if ( strpos($this->tA['rss_link'], 'http://') !== 0 && strpos($this->tA['rss_link'], 'https://') !== 0 )
            $this->tA['rss_link'] = 'http://' . $this->tA['rss_link'];
        
        $feed = fetch_feed($this->tA['rss_link']);
        
        if(is_wp_error($feed)) 
        {
            $articles = array();
            $this->tA['error_handle'] = "<div class=\"newsboard_plugin_error_handle_" . $this->tA['nbp_item'] . "\">There is a problem fetching the RSS!</div>";
        } 
        else 
        {
            $encoding = $feed->get_encoding();
            $maxitems = $feed->get_item_quantity($this->tA['number_of_news']);
            
            $rss_items = $feed->get_items(0, $maxitems);
            
            foreach($rss_items as $item)
            {   
                $article = array();
                $img = '';
                
                $title = (string) trim($item->get_title());
                $desc = (string) trim($item->get_description());
                $link = (string) trim($item->get_link());
                $perm = (string) trim($item->get_permalink());
                $date = (string) trim($item->get_date());
                $enclosures = $item->get_enclosures();
                $enclosure = end($enclosures);
                $thumbs = is_object($enclosure) ? $enclosure->get_thumbnails() : null;
                
                if(is_object($enclosure) == true && ($enclosure->get_type() == 'image/jpeg' || $enclosure->get_type() == 'image/jpg' || $enclosure->get_type() == 'image/gif' || $enclosure->get_type() == 'image/png' || $enclosure->get_medium() == 'image' || in_array( end( explode( '.', strtok( $enclosure->get_link(), '?' ) ) ), array( 'jpeg', 'jpg', 'gif', 'png' ) ) ) )
                    $img = (string) trim($enclosure->get_link());
                elseif(is_array($thumbs) && count($thumbs) > 0)
                {
                    $img = end($thumbs);
                }
                //Finds image in the content but slows down page load so it will be considered in future releases
                /*else
                {
                    $content = trim($item->get_content());
                    $img = $this->findImageInText($content, $encoding);
                }*/
                
                $itemRSS = array ( 
                  'title' => $title,
                  'desc' => $desc,
                  'link' => $link,
                  'date' => $date,
                  'img' => $img
                );
                array_push($articles, $itemRSS);
            }
        }
      
        return $articles; 
    }
    
    /**
     * Creates the HTML from RSS for the plugin ticker.
     */
    private function createRSSContent()
    {
        $arrFeeds = $this->rssParse();
        $titleCutMethod = $this->tA['title_cutting_rule'] . 'Cut';
        $textCutMethod = $this->tA['text_cutting_rule'] . 'Cut';
        for($i=0; $i<count($arrFeeds); $i++)
        {
            if($this->tA['number_of_news'] == $i)
                break;  
            
            $post_title_cut = $this->$titleCutMethod($arrFeeds[$i]['title'], $this->tA['title_cut_after'], "...");
                
            if($arrFeeds[$i]['img']!="" && $this->tA['show_thumbnails'] == 'checked="checked"')
            {
                $imagesize = getimagesize($this->tA['loader_path']);
                
                if($this->tA['image_border_on'] == 'checked="checked"')
                    $flag_border = 1;
                else
                    $flag_border = 0;
                $img_padding = intval($this->tA['thumbnail_width'] * 0.1);
                
                $image = "<td class=\"image\" style=\"width: 1%; padding-left: " . $img_padding . "px; padding-right: " . $img_padding . "px; \">
                        <div class=\"new_thumbnail\" style=\"width:" . $this->tA['thumbnail_width']. "px; height:" . $this->tA['thumbnail_height'] . "px; background: #fff url(" . $this->tA['loader_path'] . ") no-repeat center center; -webkit-background-size: " . $imagesize[0] . "px " . $imagesize[1] . "px; background-size: " . $imagesize[0] . "px " . $imagesize[1] . "px; border:" . $this->tA['image_border'] .";\"><img class=\"nbp_real_img\" style=\"display: none;\" data-src=\"" . $arrFeeds[$i]['img'] . "\" alt=\"" . $post_title_cut . "\" /></div>
                     </td>";
                
                $padding_right = max(max($this->tA['roundness_top_right'], $this->tA['roundness_bottom_right'])/2, 10);
                $content_style = "style=\"padding: 0px " . $padding_right . "px 0px 0px;\"";
            }
            else
            {
                $image = "";
                $padding_left = max(max($this->tA['roundness_top_left'], $this->tA['roundness_bottom_left'])/2, 10);
                $padding_right = max(max($this->tA['roundness_top_right'], $this->tA['roundness_bottom_right'])/2, 10);
                $content_style = "style=\"width:100%; padding: 0px " . $padding_right . "px 0px " . $padding_left . "px;\"";
            }
            
            if($this->tA['show_date'] == 'checked="checked"')
                $show_date = "<div class=\"new_date\">" . mysql2date(__($this->tA['date_format_string']), mysql2date("r", $arrFeeds[$i]['date']), true) . "</div>";
            else
                $show_date = '';
                
            if($this->tA['show_text'] == 'checked="checked"')
            {
                $show_text = "<div class=\"new_text\">" . nl2br($this->$textCutMethod(strip_tags($arrFeeds[$i]['desc']), $this->tA['text_cut_after'], "<span class=\"read_more\">&nbsp;<a href=\"#\">" . $this->tA['read_more_string'] . "</a></span>")) . "</div>";                              
            }
              
            $contentTemp[$i] = 
                "<a class=\"new_holder\" title=\"" . $post_title_cut . "\" href=\"" . $arrFeeds[$i]['link'] . "\"" . ($this->tA['open_links_in'] == "blank" ? " target=\"_blank\"" : "") . ">
                    <table class=\"new_table\">
                        <tr>
                            " . $image . "
                            <td class=\"content\" " . $content_style . ">
                            <div class=\"new_title\">" . $post_title_cut . "</div>
                                " . $show_date . "
                                " . $show_text . "
                           </td>
                        </tr>
                    </table>
                </a>";
            $post_title_cut = "";
        }       
        $this->tA['actual_news_number'] = $i;
        $this->tA['invisible_news_top'] = 0;
        if($this->tA['board_news_fit'] >= $this->tA['actual_news_number'])
        {
            $this->tA['board_news_fit'] = $this->tA['actual_news_number'];
            $this->tA['show_arrows'] = false;
            for($j=0; $j<$i; $j++)
                $this->tA['render_content'] .= $contentTemp[$j];
        }
        else
        {
            if($this->tA['actual_news_number'] < 4)
            {
                $invisibleUp = $contentTemp[$i-2] . $contentTemp[$i-1];
                $invisibleDown = $contentTemp[0] . $contentTemp[1];
                $this->tA['invisible_news'] = 4;
                $this->tA['invisible_news_top'] = 2;
            }
            else
            {
                for($k=0; $k<intval($i/2); $k++)
                    $invisibleDown .= $contentTemp[$k];
                for($k=intval($i/2); $k<$i; $k++)
                {
                    $invisibleUp .= $contentTemp[$k];
                    $this->tA['invisible_news_top']++;
                }
                $this->tA['invisible_news'] = $i;
            }
            for($j=0; $j<$i; $j++)
                $this->tA['render_content'] .= $contentTemp[$j];
            $this->tA['render_content'] = $invisibleUp . $this->tA['render_content'] . $invisibleDown;
        }
        if(count($arrFeeds) > 0)
            $this->makeBar();
    }
    
    /**
     * Builds the HTML for the ticker bar.
     */
    private function makeBar()
    {
        $buttons = '';
        if($this->tA['show_arrows'] != false)
            $buttons = "<div id=\"btn_up\" style=\"background-image: url(" . $this->tA['plugin_dir'] . "render/img/arrows.png)\"></div><div id=\"btn_down\" style=\"background-image: url(" . $this->tA['plugin_dir'] . "render/img/arrows.png)\"></div>";
            
        $this->tA['render_content'] .= "</div></div>
                <div class=\"bar\"><a href=\"http://newsboardplugin.com/wordpress-post-and-rss-plugin/\" target=\"_blank\" title=\"NewsBoard - Wordpress Post and RSS plugin\"><div id=\"nbp_logo\" style=\"background-image: url(" . $this->tA['plugin_dir'] . "images/logo.png)\"></div></a>
                    <ul>
                        <li style=\"width: 100% !important;\">
                            <div class=\"nbp_nav\">
                                " . $buttons . "
                            </div>
                        </li>
                    </ul>";
    }
}