@extends('templates.master')

@section('Title')
    @if(isSet($anime)){{ $anime->name }}@endif @if(isSet($episode_id)){{ '- Tập '. \App\DBEpisodes::GetEpisode($episode_id) }}@endif
@stop

@section('stylesheet')
@stop

@section('headerBar')
    <div id="header_bar">
        <div class="bg_overlay"></div>
    </div>
@stop

@section('header-menu-category')
    @foreach ($category_list as $i)
        <li><a href='{{ Request::root() }}/the-loai/{{ $i->id }}.anime4a'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('header-menu-country')
    @foreach ($country_list as $i)
        <li><a href='{{ Request::root() }}/quoc-gia/{{ $i->id }}.anime4a'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('MainUrl')
    <a href="{{ Request::root() }}" style="display: none" id="MainUrl"></a>
@stop

@section('content')
    <div class="breadcrumb"></div>
    <!-- Video View Region -->
    <div class="video_player">
        @if(isSet($video_type))
            @if(isSet($video)&&!is_null($video))
                @if($video_type=='google')
                    <div id="player-container">
                        <iframe id="player" src="/get-gg-video-{{ $video->id }}" width="100%" frameborder="0" allowfullscreen></iframe>
                    </div>
                @else
                    <div id="player-container">
                        <iframe id="player" src="/get-video-{{ $video->id }}" width="100%" frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif
            @else
                <div style="color: white;font-size: 18pt;width: 680px;height: 420px;">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</div>
            @endif
        @endif
    </div>
    <?php
    $tenphim = '';
    if(isSet($anime))
        $tenphim = \App\Library\MyFunction::GetFormatedName($anime->name);
    ?>
    @if(isSet($anime))
        <div class="video_detail" style="display: none" itemscope itemtype="http://schema.org/TVEpisode">
            <div style="display: none;">
                <a itemprop="url" href="{{ Request::root() }}/xem-phim/{{ \App\Library\MyFunction::GetFormatedName($anime->name) }}/{{ $anime->id }}.a4a"></a>
            </div>
            <div class="video_img">
                <img itemprop="image" class="banner" src="{{ $anime->banner }}">
                <img itemprop="image" class="img" src="{{ $anime->img }}">
            </div>
            <div class="video_info">
                <div class="video_name">
                    <span itemprop="name">{{ $anime->name }}</span>
                </div>
                <div class="video_category">
                    <?php $catArr = \App\Library\MyFunction::GetCategoryNameArray($anime->category);?>
                    <span>Thể loại: @foreach($catArr as $i)<a>{{ $i }}, </a>@endforeach</span>
                </div>
                <div class="video_ep">
                    <span>Số tập: {{ $anime->episode_new }}/<span itemprop="episodeNumber">{{ $anime->episode_total }}</span></span>
                </div>
                <div class="video_release">
                    <span>Năm sản xuất: {{ date_format($anime->release_date,"Y") }}</span>
                </div>
                <div class="video_type">
                    <span>Type: <a>{{ \App\DBType::GetName($anime->type) }}</a></span>
                </div>
                <div class="video_description" itemprop="review" itemscope itemtype="http://schema.org/Review">
                    <span itemprop="reviewBody"><?php echo $anime->description;?></span>
                    <span itemprop="author" style="display: none;">{{ Request::root() }}</span>
                </div>
            </div>
        </div>
    @endif
    <div class="video_selection">
        <script>
            $(document).ready(function (){
               $('.video_selection>.group>.item').bind('click', function (e) {
                   var url = $(this).find('a').attr('href');
                   if(url)
                       location.href = url;
               });
            });
        </script>
        <div class="group">
            <div class="title">
                Tập Phim:
            </div>
            @if(isSet($episode_list))
                @foreach ($episode_list as $i)
                    @if(isSet($episode_id))
                        @if($i->id==$episode_id)
                            <div class="item"><a class="epN active" >{{ $i->episode }}</a></div>
                        @else
                            <div class="item"><a class="epN" href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $i->id }}.a4a">{{ $i->episode }}</a></div>
                        @endif
                    @else
                        <div class="item"><a class="epN" href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $i->id }}.a4a">{{ $i->episode }}</a></div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="group">
            <div class="title">
                Fansub:
            </div>
            @if(isSet($fansub_list))
                @foreach ($fansub_list as $i)
                    @if(isSet($fansub_id))
                        @if($i->fansub_id==$fansub_id)
                            <div class="item"><b><a class="active" >{{ \App\DBFansub::GetName($i->fansub_id) }}</a></b></div>
                        @else
                            <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $episode_id }}/{{ $i->fansub_id }}.a4a">{{ \App\DBFansub::GetName($i->fansub_id) }}</a></b></div>
                        @endif
                    @else
                        <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $episode_id }}/{{ $i->fansub_id }}.a4a">{{ \App\DBFansub::GetName($i->fansub_id) }}</a></b></div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="group">
            <div class="title">
                Server:
            </div>
            @if(isSet($server_list)&&isSet($server_id))
                @foreach ($server_list as $i)
                    @if(isSet($server_id))
                        @if($i->server_id==$server_id)
                            <div class="item"><b><a class="active" >{{ \App\DBServer::GetName($i->server_id) }}</a></b></div>
                        @else
                            <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $episode_id }}/{{ $fansub_id }}/{{ $i->server_id }}.a4a">{{ \App\DBServer::GetName($i->server_id) }}</a></b></div>
                        @endif
                    @else
                        <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}/{{ $episode_id }}/{{ $fansub_id }}/{{ $i->server_id }}.a4a">{{ \App\DBServer::GetName($i->server_id) }}</a></b></div>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- START FACEBOOK COMMENT BOX -->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=289914814743628";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-comments" data-href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $anime_id }}.a4a" data-width="680" data-colorscheme="dark" data-numposts="5"></div>
        <!-- END FACEBOOK COMMENT BOX -->

        <div class="video_page_advertise">

        </div>
    </div>
    <!-- END Video View Region -->
@stop

@section('controlBar')
    <?php
    $url_download = '';
    if(isSet($episode_id)){
        $e = \App\DBEpisodes::find($episode_id);
        if(!is_null($e))
            $url_download = $e->url_download;
    }
    ?>
    @if(isSet($userSigned))
        @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful'))
            <div class="video_control_region">
                <div class="video_control">
                    <div class="item"><a class="videoInfo abutton" title="Thông Tin">Thông Tin</a></div>
                    <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                    <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                    <div class="item"><a class="download abutton" title="Download" href="{{ \App\Library\PhpAdfLy::ShortenUrl($url_download) }}" target="_blank">Download</a></div>
                    <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                    <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                    <div class="item"><a class="userCpBtn abutton " title="Danh sách Anime đang theo dõi">Danh sách Anime đang theo dõi</a></div>
                </div>
            </div>

            <!-- USER BOX -->
            @include('templates.UserBox')

        @else
            <div class="video_control_region">
                <div class="video_control">
                    <div class="item"><a class="videoInfo abutton" title="Thông Tin">Thông Tin</a></div>
                    <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                    <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                    <div class="item"><a class="download abutton" title="Download" href="{{ \App\Library\PhpAdfLy::ShortenUrl($url_download) }}" target="_blank">Download</a></div>
                    <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                    <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                    <div class="item"><a class="userCpBtn abutton" title="Danh sách Anime đang theo dõi">Danh sách Anime đang theo dõi</a></div>
                </div>
            </div>
            <script>
                $('.bookmarkBtn').bind('click', function (e) {
                    $('#userBox').fadeIn();
                });
                $('.userCpBtn').bind('click', function (e) {
                    $('#userBox').fadeIn();
                });
            </script>
        @endif
    @else
        <div class="video_control_region">
            <div class="video_control">
                <div class="item"><a class="videoInfo abutton" title="Thông Tin">Thông Tin</a></div>
                <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                <div class="item"><a class="download abutton" title="Download" href="{{ \App\Library\PhpAdfLy::ShortenUrl($url_download) }}" target="_blank">Download</a></div>
                <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                <div class="item"><a class="userCpBtn abutton" title="Danh sách Anime đang theo dõi">Danh sách Anime đang theo dõi</a></div>
            </div>
        </div>
        <script>
            $('.bookmarkBtn').bind('click', function (e) {
                $('#userBox').fadeIn();
            });
            $('.userCpBtn').bind('click', function (e) {
                $('#userBox').fadeIn();
            });
        </script>
    @endif
@stop

@section('sidebar')
    <!-- SideBar Region -->
    <div id="sidebar" style="margin-top: -383px;">
<div style="width: 300px; height: 250px;display: none;">
<script type="text/javascript">
    google_ad_client = "ca-pub-7114750780411306";
    google_ad_slot = "5442257676";
    google_ad_width = 300;
    google_ad_height = 250;
</script>
<!-- anime4a ads sidebar 2 -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
        <div class="most_view">
            <div class="titleBar">
                <span>Anime Xem Nhiều</span>
                <div class="findButtons">
                    <a class="buttonD @if($mostViewSelected == 'D') selected @endif ">D</a>
                    <span>-</span>
                    <a class="buttonW @if($mostViewSelected == 'W') selected @endif ">W</a>
                    <span>-</span>
                    <a class="buttonM @if($mostViewSelected == 'M') selected @endif ">M</a>
                    <span>-</span>
                    <a class="buttonS @if($mostViewSelected == 'S') selected @endif ">S</a>
                    <span>-</span>
                    <a class="buttonY @if($mostViewSelected == 'Y') selected @endif ">Y</a>
                </div>
            </div>
            <ul class="sidebar_items">
            </ul>
        </div>
    </div>
    <!-- END SideBar Region -->
@stop