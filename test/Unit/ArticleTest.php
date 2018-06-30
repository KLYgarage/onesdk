<?php

namespace One\Test\Unit;

use One\Model\Article;
use One\Model\Photo;
use One\Model\Page;
use One\Model\Gallery;
use One\Model\Video;
use One\Model\Model;

class ArticleTest extends \PHPUnit\Framework\TestCase
{
    protected $dummy;

    public function setUp()
    {
        $this->dummy = new Article(
            'Recusandae natus soluta similique molestiae.',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1'
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf('One\Model\Model', $this->dummy);
        $this->assertInstanceOf('One\Model\Article', $this->dummy);

        $collection =  $this->dummy->getCollection();
        $this->assertInstanceOf('One\Collection', $collection);

        $this->assertEquals($collection->toJson(), $this->dummy->toJson());
    }

    /**
     * @covers Article::attach()
     * @covers Article::attachPhoto()
     *
     * @return void
     */
    public function testAttachment()
    {
        $article = clone $this->dummy;

        $article->attach(Article::ATTACHMENT_FIELD_PHOTO, new Photo(
            'http://heydrich.com/',
            Photo::RATIO_SQUARE,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        ));

        $this->assertCount(1, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO));

        $photoAttachment = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO)[0];
        $this->assertTrue($photoAttachment instanceof Model);
        $this->assertTrue($photoAttachment instanceof Photo);

        $article->attachPhoto(new Photo(
            'http://heydrich.com/',
            Photo::RATIO_HEADLINE,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        ));

        $this->assertCount(2, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO));

        $article->attachPage(new Page(
            'Ratione veritatis hic eaque consequuntur cupiditate.',
            'Ad pariatur enim cumque atque saepe. Minima assumenda vitae ratione reiciendis. Harum est alias facere deserunt minima voluptatem.',
            'http://www.leconte.com/',
            0,
            'https://www.soelzer.de/'
        ));

        $article->attachPage(new Page(
            'Ratione veritatis hic eaque consequuntur cupiditate.',
            'Ad pariatur enim cumque atque saepe. Minima assumenda vitae ratione reiciendis. Harum est alias facere deserunt minima voluptatem.',
            'http://www.leconte.com/',
            1,
            'http://www.perez.info/app/tag/faq/'
        ));

        $this->assertCount(2, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PAGE));

        $page = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PAGE)[0];
        $this->assertEquals(1, $page->get('order'));
        $this->assertTrue(!empty($page->get('lead')));

        $article->attachGallery(new Gallery(
            'Est illum cupiditate quidem alias.',
            1,
            'http://jordan.biz/',
            'https://www.roemer.de/',
            'Ipsam quidem ut tempora incidunt officia sunt.'
        ));

        $this->assertCount(1, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_GALLERY));

        $article->attachVideo(new Video(
            'Fugit omnis expedita. Quia nisi harum dolor animi architecto velit. Nisi omnis nobis vero exercitationem.',
            'https://www.amador.com/main/explore/home/',
            0,
            'https://wang.com/wp-content/tags/index/',
            'Quia nihil fugit accusamus.'
        ));

        $this->assertCount(1, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_VIDEO));

        $this->assertEquals(
            count(Article::getPossibleAttachment()),
            count($article->getAttachments())
        );
    }
}
