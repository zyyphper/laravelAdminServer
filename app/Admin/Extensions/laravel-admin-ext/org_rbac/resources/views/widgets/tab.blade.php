<style>
    .tab-active {
        border-top: #17a2b8 solid 3px;
    }
</style>
<div {!! $attributes !!}>
    <ul class="nav nav-tabs">
        @foreach($tabs as $id => $tab)
            @if($tab['type'] == \Encore\Admin\Widgets\Tab::TYPE_CONTENT)
                <li {{ $id == $active ? 'class=tab-active' : '' }} onclick="tabSelected(this)"><a href="#tab_{{ $tab['id'] }}" style="padding: 0 10px" data-toggle="tab">{{ $tab['title'] }}</a></li>
            @elseif($tab['type'] == \Encore\Admin\Widgets\Tab::TYPE_LINK)
                <li {{ $id == $active ? 'class=tab-active' : '' }}  onclick="tabSelected(this)"><a href="{{ $tab['href'] }}">{{ $tab['title'] }}</a></li>
            @endif
        @endforeach

        @if (!empty($dropDown))
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Dropdown <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @foreach($dropDown as $link)
                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
        <li class="float-right header">{{ $title }}</li>
    </ul>
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
        <div class="tab-pane {{ $id == $active ? 'active' : '' }}" id="tab_{{ $tab['id'] }}">
            @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
        </div>
        @endforeach

    </div>
</div>
<script>
    function tabSelected(that) {
        let tabs = $(".nav-tabs li")
        tabs.each(function () {
            $(this).removeClass('tab-active')
        })
        $(that).addClass("tab-active")
    }
</script>
