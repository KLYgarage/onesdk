
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:One" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="One.html">One</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:One_Http" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="One/Http.html">Http</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:One_Http_BufferStream" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/BufferStream.html">BufferStream</a>                    </div>                </li>                            <li data-name="class:One_Http_FnStream" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/FnStream.html">FnStream</a>                    </div>                </li>                            <li data-name="class:One_Http_Message" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/Message.html">Message</a>                    </div>                </li>                            <li data-name="class:One_Http_PumpStream" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/PumpStream.html">PumpStream</a>                    </div>                </li>                            <li data-name="class:One_Http_Request" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/Request.html">Request</a>                    </div>                </li>                            <li data-name="class:One_Http_Response" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/Response.html">Response</a>                    </div>                </li>                            <li data-name="class:One_Http_Stream" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Http/Stream.html">Stream</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:One_Model" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="One/Model.html">Model</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:One_Model_Article" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Article.html">Article</a>                    </div>                </li>                            <li data-name="class:One_Model_Gallery" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Gallery.html">Gallery</a>                    </div>                </li>                            <li data-name="class:One_Model_Model" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Model.html">Model</a>                    </div>                </li>                            <li data-name="class:One_Model_Page" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Page.html">Page</a>                    </div>                </li>                            <li data-name="class:One_Model_Photo" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Photo.html">Photo</a>                    </div>                </li>                            <li data-name="class:One_Model_Video" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="One/Model/Video.html">Video</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:One_Collection" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/Collection.html">Collection</a>                    </div>                </li>                            <li data-name="class:One_DummyLogger" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/DummyLogger.html">DummyLogger</a>                    </div>                </li>                            <li data-name="class:One_FactoryArticle" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/FactoryArticle.html">FactoryArticle</a>                    </div>                </li>                            <li data-name="class:One_FactoryGallery" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/FactoryGallery.html">FactoryGallery</a>                    </div>                </li>                            <li data-name="class:One_FactoryPhoto" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/FactoryPhoto.html">FactoryPhoto</a>                    </div>                </li>                            <li data-name="class:One_FactoryUri" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/FactoryUri.html">FactoryUri</a>                    </div>                </li>                            <li data-name="class:One_JsonInterface" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/JsonInterface.html">JsonInterface</a>                    </div>                </li>                            <li data-name="class:One_Publisher" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/Publisher.html">Publisher</a>                    </div>                </li>                            <li data-name="class:One_ToArrayInterface" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/ToArrayInterface.html">ToArrayInterface</a>                    </div>                </li>                            <li data-name="class:One_Uri" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="One/Uri.html">Uri</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:one" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="one.html">one</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:one_FormatMapping" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="one/FormatMapping.html">FormatMapping</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "One.html", "name": "One", "doc": "Namespace One"},{"type": "Namespace", "link": "One/Http.html", "name": "One\\Http", "doc": "Namespace One\\Http"},{"type": "Namespace", "link": "One/Model.html", "name": "One\\Model", "doc": "Namespace One\\Model"},{"type": "Namespace", "link": "one.html", "name": "one", "doc": "Namespace one"},
            {"type": "Interface", "fromName": "One", "fromLink": "One.html", "link": "One/JsonInterface.html", "name": "One\\JsonInterface", "doc": "&quot;JSON usage interface&quot;"},
                                                        {"type": "Method", "fromName": "One\\JsonInterface", "fromLink": "One/JsonInterface.html", "link": "One/JsonInterface.html#method_toJson", "name": "One\\JsonInterface::toJson", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\JsonInterface", "fromLink": "One/JsonInterface.html", "link": "One/JsonInterface.html#method_fromJson", "name": "One\\JsonInterface::fromJson", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "One", "fromLink": "One.html", "link": "One/ToArrayInterface.html", "name": "One\\ToArrayInterface", "doc": "&quot;Interface to facilitate array conversion on object&quot;"},
                                                        {"type": "Method", "fromName": "One\\ToArrayInterface", "fromLink": "One/ToArrayInterface.html", "link": "One/ToArrayInterface.html#method_toArray", "name": "One\\ToArrayInterface::toArray", "doc": "&quot;&quot;"},
            
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/Collection.html", "name": "One\\Collection", "doc": "&quot;Collection class&quot;"},
                                                        {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method___construct", "name": "One\\Collection::__construct", "doc": "&quot;constructor&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_offsetExists", "name": "One\\Collection::offsetExists", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_offsetGet", "name": "One\\Collection::offsetGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_offsetSet", "name": "One\\Collection::offsetSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_offsetUnset", "name": "One\\Collection::offsetUnset", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_getIterator", "name": "One\\Collection::getIterator", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_count", "name": "One\\Collection::count", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_toArray", "name": "One\\Collection::toArray", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_toJson", "name": "One\\Collection::toJson", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_fromJson", "name": "One\\Collection::fromJson", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_get", "name": "One\\Collection::get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_set", "name": "One\\Collection::set", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_add", "name": "One\\Collection::add", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_map", "name": "One\\Collection::map", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Collection", "fromLink": "One/Collection.html", "link": "One/Collection.html#method_filter", "name": "One\\Collection::filter", "doc": "&quot;filter the props againt rule on callback\nIMMUTABLE&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/DummyLogger.html", "name": "One\\DummyLogger", "doc": "&quot;dummy logger&quot;"},
                                                        {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_emergency", "name": "One\\DummyLogger::emergency", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_alert", "name": "One\\DummyLogger::alert", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_critical", "name": "One\\DummyLogger::critical", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_error", "name": "One\\DummyLogger::error", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_warning", "name": "One\\DummyLogger::warning", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_notice", "name": "One\\DummyLogger::notice", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_info", "name": "One\\DummyLogger::info", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_debug", "name": "One\\DummyLogger::debug", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\DummyLogger", "fromLink": "One/DummyLogger.html", "link": "One/DummyLogger.html#method_log", "name": "One\\DummyLogger::log", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/FactoryArticle.html", "name": "One\\FactoryArticle", "doc": "&quot;FactoryArticle Class&quot;"},
                                                        {"type": "Method", "fromName": "One\\FactoryArticle", "fromLink": "One/FactoryArticle.html", "link": "One/FactoryArticle.html#method_create", "name": "One\\FactoryArticle::create", "doc": "&quot;Create article&quot;"},
                    {"type": "Method", "fromName": "One\\FactoryArticle", "fromLink": "One/FactoryArticle.html", "link": "One/FactoryArticle.html#method_createArticle", "name": "One\\FactoryArticle::createArticle", "doc": "&quot;Create Article Object&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/FactoryGallery.html", "name": "One\\FactoryGallery", "doc": "&quot;FactoryGallery Class&quot;"},
                                                        {"type": "Method", "fromName": "One\\FactoryGallery", "fromLink": "One/FactoryGallery.html", "link": "One/FactoryGallery.html#method_create", "name": "One\\FactoryGallery::create", "doc": "&quot;function Create attachment Gallery&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/FactoryPhoto.html", "name": "One\\FactoryPhoto", "doc": "&quot;FactoryPhoto Class&quot;"},
                                                        {"type": "Method", "fromName": "One\\FactoryPhoto", "fromLink": "One/FactoryPhoto.html", "link": "One/FactoryPhoto.html#method_create", "name": "One\\FactoryPhoto::create", "doc": "&quot;function Create Photo Attachment&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/FactoryUri.html", "name": "One\\FactoryUri", "doc": "&quot;FactoryUri Class&quot;"},
                                                        {"type": "Method", "fromName": "One\\FactoryUri", "fromLink": "One/FactoryUri.html", "link": "One/FactoryUri.html#method_create", "name": "One\\FactoryUri::create", "doc": "&quot;function Create Uri&quot;"},
                    {"type": "Method", "fromName": "One\\FactoryUri", "fromLink": "One/FactoryUri.html", "link": "One/FactoryUri.html#method_createFromString", "name": "One\\FactoryUri::createFromString", "doc": "&quot;function for Create Uri From Server&quot;"},
                    {"type": "Method", "fromName": "One\\FactoryUri", "fromLink": "One/FactoryUri.html", "link": "One/FactoryUri.html#method_createFromServer", "name": "One\\FactoryUri::createFromServer", "doc": "&quot;function for Create Uri From Server&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/BufferStream.html", "name": "One\\Http\\BufferStream", "doc": "&quot;Provides a buffer stream that can be written to to fill a buffer, and read\nfrom to remove bytes from the buffer.&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method___construct", "name": "One\\Http\\BufferStream::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method___toString", "name": "One\\Http\\BufferStream::__toString", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_getContents", "name": "One\\Http\\BufferStream::getContents", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_close", "name": "One\\Http\\BufferStream::close", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_detach", "name": "One\\Http\\BufferStream::detach", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_getSize", "name": "One\\Http\\BufferStream::getSize", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_isReadable", "name": "One\\Http\\BufferStream::isReadable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_isWritable", "name": "One\\Http\\BufferStream::isWritable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_isSeekable", "name": "One\\Http\\BufferStream::isSeekable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_rewind", "name": "One\\Http\\BufferStream::rewind", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_seek", "name": "One\\Http\\BufferStream::seek", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_eof", "name": "One\\Http\\BufferStream::eof", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_tell", "name": "One\\Http\\BufferStream::tell", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_read", "name": "One\\Http\\BufferStream::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_write", "name": "One\\Http\\BufferStream::write", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\BufferStream", "fromLink": "One/Http/BufferStream.html", "link": "One/Http/BufferStream.html#method_getMetadata", "name": "One\\Http\\BufferStream::getMetadata", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/FnStream.html", "name": "One\\Http\\FnStream", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method___construct", "name": "One\\Http\\FnStream::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method___destruct", "name": "One\\Http\\FnStream::__destruct", "doc": "&quot;The close method is called on the underlying stream only if possible.&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method___get", "name": "One\\Http\\FnStream::__get", "doc": "&quot;Lazily determine which methods are not implemented.&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method___wakeup", "name": "One\\Http\\FnStream::__wakeup", "doc": "&quot;An unserialize would allow the __destruct to run when the unserialized value goes out of scope.&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method___toString", "name": "One\\Http\\FnStream::__toString", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_decorate", "name": "One\\Http\\FnStream::decorate", "doc": "&quot;Adds custom functionality to an underlying stream by intercepting\nspecific method calls.&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_close", "name": "One\\Http\\FnStream::close", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_detach", "name": "One\\Http\\FnStream::detach", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_getSize", "name": "One\\Http\\FnStream::getSize", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_tell", "name": "One\\Http\\FnStream::tell", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_eof", "name": "One\\Http\\FnStream::eof", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_isSeekable", "name": "One\\Http\\FnStream::isSeekable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_rewind", "name": "One\\Http\\FnStream::rewind", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_seek", "name": "One\\Http\\FnStream::seek", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_isWritable", "name": "One\\Http\\FnStream::isWritable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_write", "name": "One\\Http\\FnStream::write", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_isReadable", "name": "One\\Http\\FnStream::isReadable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_read", "name": "One\\Http\\FnStream::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_getContents", "name": "One\\Http\\FnStream::getContents", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\FnStream", "fromLink": "One/Http/FnStream.html", "link": "One/Http/FnStream.html#method_getMetadata", "name": "One\\Http\\FnStream::getMetadata", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/Message.html", "name": "One\\Http\\Message", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_getProtocolVersion", "name": "One\\Http\\Message::getProtocolVersion", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_withProtocolVersion", "name": "One\\Http\\Message::withProtocolVersion", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_getHeaders", "name": "One\\Http\\Message::getHeaders", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_hasHeader", "name": "One\\Http\\Message::hasHeader", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_getHeader", "name": "One\\Http\\Message::getHeader", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_getHeaderLine", "name": "One\\Http\\Message::getHeaderLine", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_withHeader", "name": "One\\Http\\Message::withHeader", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_withAddedHeader", "name": "One\\Http\\Message::withAddedHeader", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_withoutHeader", "name": "One\\Http\\Message::withoutHeader", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_getBody", "name": "One\\Http\\Message::getBody", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_withBody", "name": "One\\Http\\Message::withBody", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_setHeaders", "name": "One\\Http\\Message::setHeaders", "doc": "&quot;Set header&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Message", "fromLink": "One/Http/Message.html", "link": "One/Http/Message.html#method_trimHeaderValues", "name": "One\\Http\\Message::trimHeaderValues", "doc": "&quot;Trims whitespace from the header values.&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/PumpStream.html", "name": "One\\Http\\PumpStream", "doc": "&quot;Provides a read only stream that pumps data from a PHP callable.&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method___construct", "name": "One\\Http\\PumpStream::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method___toString", "name": "One\\Http\\PumpStream::__toString", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_close", "name": "One\\Http\\PumpStream::close", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_detach", "name": "One\\Http\\PumpStream::detach", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_getSize", "name": "One\\Http\\PumpStream::getSize", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_tell", "name": "One\\Http\\PumpStream::tell", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_eof", "name": "One\\Http\\PumpStream::eof", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_isSeekable", "name": "One\\Http\\PumpStream::isSeekable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_rewind", "name": "One\\Http\\PumpStream::rewind", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_seek", "name": "One\\Http\\PumpStream::seek", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_isWritable", "name": "One\\Http\\PumpStream::isWritable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_write", "name": "One\\Http\\PumpStream::write", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_isReadable", "name": "One\\Http\\PumpStream::isReadable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_read", "name": "One\\Http\\PumpStream::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_getContents", "name": "One\\Http\\PumpStream::getContents", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\PumpStream", "fromLink": "One/Http/PumpStream.html", "link": "One/Http/PumpStream.html#method_getMetadata", "name": "One\\Http\\PumpStream::getMetadata", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/Request.html", "name": "One\\Http\\Request", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method___construct", "name": "One\\Http\\Request::__construct", "doc": "&quot;Default Constructor&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_getRequestTarget", "name": "One\\Http\\Request::getRequestTarget", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_withRequestTarget", "name": "One\\Http\\Request::withRequestTarget", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_getMethod", "name": "One\\Http\\Request::getMethod", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_withMethod", "name": "One\\Http\\Request::withMethod", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_getUri", "name": "One\\Http\\Request::getUri", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Request", "fromLink": "One/Http/Request.html", "link": "One/Http/Request.html#method_withUri", "name": "One\\Http\\Request::withUri", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/Response.html", "name": "One\\Http\\Response", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\Response", "fromLink": "One/Http/Response.html", "link": "One/Http/Response.html#method___construct", "name": "One\\Http\\Response::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Response", "fromLink": "One/Http/Response.html", "link": "One/Http/Response.html#method_getStatusCode", "name": "One\\Http\\Response::getStatusCode", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Response", "fromLink": "One/Http/Response.html", "link": "One/Http/Response.html#method_getReasonPhrase", "name": "One\\Http\\Response::getReasonPhrase", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Response", "fromLink": "One/Http/Response.html", "link": "One/Http/Response.html#method_withStatus", "name": "One\\Http\\Response::withStatus", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Http", "fromLink": "One/Http.html", "link": "One/Http/Stream.html", "name": "One\\Http\\Stream", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method___construct", "name": "One\\Http\\Stream::__construct", "doc": "&quot;This constructor accepts an associative array of options.&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method___destruct", "name": "One\\Http\\Stream::__destruct", "doc": "&quot;Closes the stream when the destructed&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method___toString", "name": "One\\Http\\Stream::__toString", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_getContents", "name": "One\\Http\\Stream::getContents", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_close", "name": "One\\Http\\Stream::close", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_detach", "name": "One\\Http\\Stream::detach", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_getSize", "name": "One\\Http\\Stream::getSize", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_isReadable", "name": "One\\Http\\Stream::isReadable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_isWritable", "name": "One\\Http\\Stream::isWritable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_isSeekable", "name": "One\\Http\\Stream::isSeekable", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_eof", "name": "One\\Http\\Stream::eof", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_tell", "name": "One\\Http\\Stream::tell", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_rewind", "name": "One\\Http\\Stream::rewind", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_seek", "name": "One\\Http\\Stream::seek", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_read", "name": "One\\Http\\Stream::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_write", "name": "One\\Http\\Stream::write", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Http\\Stream", "fromLink": "One/Http/Stream.html", "link": "One/Http/Stream.html#method_getMetadata", "name": "One\\Http\\Stream::getMetadata", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/JsonInterface.html", "name": "One\\JsonInterface", "doc": "&quot;JSON usage interface&quot;"},
                                                        {"type": "Method", "fromName": "One\\JsonInterface", "fromLink": "One/JsonInterface.html", "link": "One/JsonInterface.html#method_toJson", "name": "One\\JsonInterface::toJson", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\JsonInterface", "fromLink": "One/JsonInterface.html", "link": "One/JsonInterface.html#method_fromJson", "name": "One\\JsonInterface::fromJson", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Article.html", "name": "One\\Model\\Article", "doc": "&quot;Article Class&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method___construct", "name": "One\\Model\\Article::__construct", "doc": "&quot;constructor&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_getPossibleAttachment", "name": "One\\Model\\Article::getPossibleAttachment", "doc": "&quot;get ALL Possible attachment for an article, return arrays of field name. Used for consistency accross sdk\nleveraging php version 5.3 cannot use array constant&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_getDeleteableAttachment", "name": "One\\Model\\Article::getDeleteableAttachment", "doc": "&quot;get deleteable attachment for constant usage across sdk&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_setId", "name": "One\\Model\\Article::setId", "doc": "&quot;setIdentifier from rest api response&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_getId", "name": "One\\Model\\Article::getId", "doc": "&quot;getIdentifier set before&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_hasAttachment", "name": "One\\Model\\Article::hasAttachment", "doc": "&quot;check if this object has attachment assigned to it&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_getAttachmentByField", "name": "One\\Model\\Article::getAttachmentByField", "doc": "&quot;getAttachment based on fields&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_getAttachments", "name": "One\\Model\\Article::getAttachments", "doc": "&quot;get ALL attachment assigned to this object&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_attach", "name": "One\\Model\\Article::attach", "doc": "&quot;add attach an attachment to this model&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_attachPhoto", "name": "One\\Model\\Article::attachPhoto", "doc": "&quot;Attach Photo Attachment to article&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_attachPage", "name": "One\\Model\\Article::attachPage", "doc": "&quot;Attach Paging&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_attachGallery", "name": "One\\Model\\Article::attachGallery", "doc": "&quot;Attach gallery here&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Article", "fromLink": "One/Model/Article.html", "link": "One/Model/Article.html#method_attachVideo", "name": "One\\Model\\Article::attachVideo", "doc": "&quot;attach Video&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Gallery.html", "name": "One\\Model\\Gallery", "doc": "&quot;Attachment Gallery class&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Gallery", "fromLink": "One/Model/Gallery.html", "link": "One/Model/Gallery.html#method___construct", "name": "One\\Model\\Gallery::__construct", "doc": "&quot;constructor&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Model.html", "name": "One\\Model\\Model", "doc": "&quot;Model base class&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method___call", "name": "One\\Model\\Model::__call", "doc": "&quot;proxy method to chain it to Collection class&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_getCollection", "name": "One\\Model\\Model::getCollection", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_withCollection", "name": "One\\Model\\Model::withCollection", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_filterStringInstance", "name": "One\\Model\\Model::filterStringInstance", "doc": "&quot;Clean non parseable char from string&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_filterUriInstance", "name": "One\\Model\\Model::filterUriInstance", "doc": "&quot;Make Sure Uri is a Psr\\Http\\Message\\UriInterface instance&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_filterDateInstance", "name": "One\\Model\\Model::filterDateInstance", "doc": "&quot;Make Sure Date in string with correct format state&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_formatDate", "name": "One\\Model\\Model::formatDate", "doc": "&quot;format date into required format&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_createLeadFromBody", "name": "One\\Model\\Model::createLeadFromBody", "doc": "&quot;create lead\/synopsis from body content if not available&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_get", "name": "One\\Model\\Model::get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_set", "name": "One\\Model\\Model::set", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_add", "name": "One\\Model\\Model::add", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Model\\Model", "fromLink": "One/Model/Model.html", "link": "One/Model/Model.html#method_map", "name": "One\\Model\\Model::map", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Page.html", "name": "One\\Model\\Page", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Page", "fromLink": "One/Model/Page.html", "link": "One/Model/Page.html#method___construct", "name": "One\\Model\\Page::__construct", "doc": "&quot;constuctor&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Photo.html", "name": "One\\Model\\Photo", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Photo", "fromLink": "One/Model/Photo.html", "link": "One/Model/Photo.html#method___construct", "name": "One\\Model\\Photo::__construct", "doc": "&quot;constructor&quot;"},
            
            {"type": "Class", "fromName": "One\\Model", "fromLink": "One/Model.html", "link": "One/Model/Video.html", "name": "One\\Model\\Video", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "One\\Model\\Video", "fromLink": "One/Model/Video.html", "link": "One/Model/Video.html#method___construct", "name": "One\\Model\\Video::__construct", "doc": "&quot;constructor&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/Publisher.html", "name": "One\\Publisher", "doc": "&quot;Publisher class\nmain class to be used that interfacing to the API&quot;"},
                                                        {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method___construct", "name": "One\\Publisher::__construct", "doc": "&quot;constructor&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_recycleToken", "name": "One\\Publisher::recycleToken", "doc": "&quot;recycleToken from callback. If use external token storage could leveraged on this&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_setTokenSaver", "name": "One\\Publisher::setTokenSaver", "doc": "&quot;set Token Saver&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_getTokenSaver", "name": "One\\Publisher::getTokenSaver", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_submitArticle", "name": "One\\Publisher::submitArticle", "doc": "&quot;submitting article here, return new Object cloned from original&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_submitAttachment", "name": "One\\Publisher::submitAttachment", "doc": "&quot;submit each attachment of an article here&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_getArticle", "name": "One\\Publisher::getArticle", "doc": "&quot;get article from rest API&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_listArticle", "name": "One\\Publisher::listArticle", "doc": "&quot;get list article by publisher&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_deleteArticle", "name": "One\\Publisher::deleteArticle", "doc": "&quot;delete article based on id&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_deleteAttachment", "name": "One\\Publisher::deleteAttachment", "doc": "&quot;delete attachment of an article&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_get", "name": "One\\Publisher::get", "doc": "&quot;get proxy&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_post", "name": "One\\Publisher::post", "doc": "&quot;post proxy&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_delete", "name": "One\\Publisher::delete", "doc": "&quot;delete proxy&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_setLogger", "name": "One\\Publisher::setLogger", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "One\\Publisher", "fromLink": "One/Publisher.html", "link": "One/Publisher.html#method_getRestServer", "name": "One\\Publisher::getRestServer", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/ToArrayInterface.html", "name": "One\\ToArrayInterface", "doc": "&quot;Interface to facilitate array conversion on object&quot;"},
                                                        {"type": "Method", "fromName": "One\\ToArrayInterface", "fromLink": "One/ToArrayInterface.html", "link": "One/ToArrayInterface.html#method_toArray", "name": "One\\ToArrayInterface::toArray", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "One", "fromLink": "One.html", "link": "One/Uri.html", "name": "One\\Uri", "doc": "&quot;Uri class implementation to ease migration when using other framework vendor. Use psr-7 standard&quot;"},
                                                        {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method___construct", "name": "One\\Uri::__construct", "doc": "&quot;Instance new Uri.&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method___toString", "name": "One\\Uri::__toString", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getScheme", "name": "One\\Uri::getScheme", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getAuthority", "name": "One\\Uri::getAuthority", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getUserInfo", "name": "One\\Uri::getUserInfo", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getHost", "name": "One\\Uri::getHost", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getPort", "name": "One\\Uri::getPort", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getPath", "name": "One\\Uri::getPath", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getQuery", "name": "One\\Uri::getQuery", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getFragment", "name": "One\\Uri::getFragment", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withScheme", "name": "One\\Uri::withScheme", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withUserInfo", "name": "One\\Uri::withUserInfo", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withHost", "name": "One\\Uri::withHost", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withPort", "name": "One\\Uri::withPort", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withPath", "name": "One\\Uri::withPath", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withQuery", "name": "One\\Uri::withQuery", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withFragment", "name": "One\\Uri::withFragment", "doc": "&quot;{@inheritdoc}&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_getBaseUrl", "name": "One\\Uri::getBaseUrl", "doc": "&quot;get Base Url&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_withString", "name": "One\\Uri::withString", "doc": "&quot;withString function.&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_filterScheme", "name": "One\\Uri::filterScheme", "doc": "&quot;filter scheme given to only allow certain scheme, no file:\/\/ or ftp:\/\/ or other scheme because its http message uri interface&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_filterPort", "name": "One\\Uri::filterPort", "doc": "&quot;Filter allowable port to minimize risk&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_filterPath", "name": "One\\Uri::filterPath", "doc": "&quot;Path allowed chars filter, no weird path on uri yes?.&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_filterQuery", "name": "One\\Uri::filterQuery", "doc": "&quot;replace query to clear not allowed chars&quot;"},
                    {"type": "Method", "fromName": "One\\Uri", "fromLink": "One/Uri.html", "link": "One/Uri.html#method_hasStandardPort", "name": "One\\Uri::hasStandardPort", "doc": "&quot;cek if current uri scheme use standard port&quot;"},
            
            {"type": "Class", "fromName": "one", "fromLink": "one.html", "link": "one/FormatMapping.html", "name": "one\\FormatMapping", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "one\\FormatMapping", "fromLink": "one/FormatMapping.html", "link": "one/FormatMapping.html#method___construct", "name": "one\\FormatMapping::__construct", "doc": "&quot;Construct JSON attributes&quot;"},
                    {"type": "Method", "fromName": "one\\FormatMapping", "fromLink": "one/FormatMapping.html", "link": "one/FormatMapping.html#method_article", "name": "one\\FormatMapping::article", "doc": "&quot;map a single article to main attributes in Article Class&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


