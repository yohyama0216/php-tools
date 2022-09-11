<?php

require('./ClashRoyale.php');

class SqlGenerator {
    private $range = [];
    private $filePath = "./pages/page%s.json";
    private $files = [];
    private $postCount = 24;

    public function __construct()
    {
        $this->range = range(1,2);
        foreach($this->range as $num) {
            $this->files[] = sprintf($this->filePath,$num);
        }
    }

    public function generate()
    {
        foreach($this->files as $file) {
            $data = $this->read($file);
            $this->generateSQL($data);
        }
    }

    private function read($file)
    {
        $content = file_get_contents($file);
        return json_decode($content,true);
    }

    private function convertData($battleResultList)
    {
        $converted = [];
        foreach ($battleResultList as $battleResult) {
            $converted[] = [
                'title' => '',
                'post' => '',
                'post_id' => ''
            ];
        }
        return $converted;
    }

    private function generateSQL($data)
    {
        // $this->generateTermSQl($data);
        // $this->generateTermTaxnomySQL($data);
        $this->generatePostsSQL($data);
        // $this->generateTermRelationshipSQL($data);
        // $this->generateUpdateTermTaxnomySQL($data);
    }

    private function generateTermSQl()
    {
        // apiから取得して作る？
        $template = <<<EOT
        INSERT INTO `wp_clashroyale_search_com_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES (NULL, '%s', '%s', '0');,
        EOT;
    }

    private function generateTermTaxnomySQL()
    {
       
    }

    private function generateUpdateTermTaxnomySQL()
    {
        // countはどうする？　relationship入れてからにする
    }

    private function generatePostsSQL($data)
    {
        foreach($data as $item) {
            echo $this->getInsertSQL($item);
            $this->postCount += 1;
        }
    }

    private function getInsertSQL($item) {
        $postId = $this->postCount;
        $postName = $this->createPostName($item);
        $slug = 'slug-'.$postName;
        $replayMovieId = $this->getReplayMovieId($item);
        $pubDate = '2022-06-25 13:00:00';
        $postContent = <<<EOT
        <!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v={$replayMovieId}\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\r\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\r\nhttps://www.youtube.com/watch?v={$replayMovieId}\r\n</div></figure>\r\n<!-- /wp:embed -->
        EOT;
        $values = <<<EOT
        ({$postId}, '1', '{$pubDate}', '{$pubDate}', '{$postContent}', '{$postName}', '', 'publish', 'open', 'open', '', '{$slug}', '', '', '{$pubDate}', '{$pubDate}', '', '0', 'https://clashroyale-search.com/?p={$postId}', '0', 'post', '', '0')
        EOT;
        $template = <<<EOT
        INSERT INTO `wp_clashroyale_search_com_posts` 
        (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES {$values};
        EOT;
        return $template;
    }

    private function createPostName($item)
    {
        $format = '%s VS %s';
        $winnerDeckName = ClashRoyale::getDeckNameFromCopyUrl($item['players']['winner']['deckCopyUrl']);
        $loserDeckName = ClashRoyale::getDeckNameFromCopyUrl($item['players']['loser']['deckCopyUrl']);
        return sprintf($format, $winnerDeckName, $loserDeckName);
    }

    private function getReplayMovieId($item)
    {
        preg_match('/embed.(.*)$/',$item['replayUrl'],$matches);
        return $matches[1];
    }

    private function generateTermRelationshipSQL()
    {
        $template = <<<EOT
        INSERT INTO `wp_clashroyale_search_com_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES (NULL, '%s', '%s', '0');,
        EOT;
    }
}

$SqlGenerator = new SqlGenerator();
$SqlGenerator->generate();
