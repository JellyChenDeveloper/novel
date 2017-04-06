<?php
namespace app\index\controller;

//ini_set('memory_limit', '512M');
use HtmlParser\ParserDom;
use think\Controller;

class Index extends Controller {

    private $baseurl  = 'http://m.111bz.org/';
    private $baseurl1 = 'http://www.111bz.org/';

    public function index($curpage = 0) {
        $topurl = $this->baseurl . 'top/';
        if ($curpage != 0) {
            $topurl = $topurl . 'allvisit_' . $curpage . '/';
        }

        $sHtml = file_get_contents($topurl);
        $html  = new ParserDom($sHtml);

        $articles = array();
        foreach ($html->find('ul.xbk') as $article) {
            $item['title']   = trim($article->find('.tjxs span', 0)->find('a', 0)->getPlainText());
            $item['url']     = $article->find('.tjxs span', 0)->find('a', 0)->getAttr('href');
            $item['auth']    = trim($article->find('.tjxs span', 1)->find('a', 0)->getPlainText());
            $item['authurl'] = $article->find('.tjxs span', 1)->find('a', 0)->getAttr('href');
            $item['picurl']  = $article->find('.tjimg a img', 0)->getAttr('src');
            $item['details'] = trim($article->find('.tjxs span', 2)->getPlainText());
            $articles[]      = $item;
        }
        $pages = array();

        for ($i = 0; $i < 4; $i++) {
            if ($href = $html->find('div.page a', $i)) {
                $tmpPage['href']  = $href->getAttr('href');
                $tmpPage['name']  = $href->getPlainText();
                $pages['links'][] = $tmpPage;
            }
        }
        $total = $html->find('div.page2', 0)->getPlainText();
        preg_match("/(\d+)\/(\d+)/i", $total, $matches);
        $pages['num']['cur']   = $matches[1];
        $pages['num']['total'] = $matches[2];

        $this->assign('articles', $articles);
        $this->assign('pages', $pages);

        return $this->fetch();
    }

    public function chapters($cata = 0, $bookno = 0) {
        $chapurl = $this->baseurl1 . $cata . '_' . $bookno . '/';

        $sHtml = file_get_contents($chapurl);
        $html  = new ParserDom($sHtml);

        $chapters = $html->find('div.box_con div#list dl', 0)->outerHtml();
        $bookname = $html->find('div#info h1',0)->getPlainText();

        $this->assign('name', $bookname);
        $this->assign('chapters', $chapters);

        return $this->fetch();
    }

    public function content($cata = 0, $bookno = 0, $chapno) {
        $conturl = $this->baseurl . $cata . '_' . $bookno . '/' . $chapno . '.html';

        $sHtml = file_get_contents($conturl);
        $html  = new ParserDom($sHtml);

        $name    = $html->find('header#header div.zhong', 0)->getPlainText();
        $content = $html->find('article#nr', 0)->innerHtml();

        foreach ($html->find('div.nr_page a.dise') as $tpage) {
            if ($tpage->getPlainText() == '上一章') {
                $page['prev'] = $tpage->getAttr('href');
            }
            if ($tpage->getPlainText() == '下一章') {
                $page['next'] = $tpage->getAttr('href');
            }
        }
        $page['mulu'] = '/' . $cata . '_' . $bookno . '/';

        $this->assign('name', $name);
        $this->assign('content', $content);
        $this->assign('page', $page);

        return $this->fetch();
    }
}
