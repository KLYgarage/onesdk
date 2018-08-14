<?php declare(strict_types=1);

namespace One\Test;

use One\Model\Article;
use One\Model\Photo;
use One\Validator\PhotoAttachmentsValidator;

class PhotoAttachmentsValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Article
     * @var \One\Model\Article
     */
    private $dummy;

    protected function setUp(): void
    {
        $this->dummy = new Article(
            'Recusandae natus soluta similique molestiae.',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1'
        );
    }

    public function testValidatePhotoHasVertical(): void
    {
        $photo1 = new Photo(
            'http://heydrich.com/',
            Photo::RATIO_SQUARE,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        );

        $photo2 = new Photo(
            'http://heydrich.com/',
            Photo::RATIO_VERTICAL,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        );

        $this->dummy->attachPhoto($photo1)->attachPhoto($photo2);

        $this->assertNotNull($this->dummy);

        $photoAttachmentsValidator = new PhotoAttachmentsValidator(
            $this->dummy->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO)
        );

        $photoAttachmentsValidator->checkHasRatio(Photo::RATIO_VERTICAL);

        $this->assertNotFalse($photoAttachmentsValidator->validate());
    }

    public function testValidatePhotoNotHaveVertical(): void
    {
        $photo1 = new Photo(
            'http://heydrich.com/',
            Photo::RATIO_SQUARE,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        );

        $photo2 = new Photo(
            'http://heydrich.com/',
            Photo::RATIO_SQUARE,
            'Repellat nesciunt ipsum.',
            'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
        );

        $dummy2 = new Article(
            'Recusandae natus soluta similique molestiae.',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1'
        );


        $dummy2->attachPhoto($photo1)->attachPhoto($photo2);

        $this->assertNotNull($this->dummy);

        $photoAttachmentsValidator = new PhotoAttachmentsValidator(
            $dummy2->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO)
        );

        $this->expectException(\Throwable::class);

        $photoAttachmentsValidator->checkHasRatio(Photo::RATIO_VERTICAL, true);
    }
}
