<style>
    /* CSS goes here */
    #wxWrap {
        width: 350px;
        background: #EEE; /* Old browsers */
        background: -moz-linear-gradient(top, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(240,240,240,1)), color-stop(100%,rgba(224,224,224,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, rgba(240,240,240,1) 0%,rgba(224,224,224,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, rgba(240,240,240,1) 0%,rgba(224,224,224,1) 100%); /* Opera11.10+ */
        background: -ms-linear-gradient(top, rgba(240,240,240,1) 0%,rgba(224,224,224,1) 100%); /* IE10+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f0f0f0', endColorstr='#e0e0e0',GradientType=0 ); /* IE6-9 */
        background: linear-gradient(top, rgba(240,240,240,1) 0%,rgba(224,224,224,1) 100%); /* W3C */
        padding: 2px 13px 2px 11px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
</style>
<div style="float: right; width: 320px;" id="h_weather">
    <div id="wxWrap">
        <span id="wxIntro">
            ハワイ時間: {{ Carbon\Carbon::now('Pacific/Honolulu')->format('Y/m/d Ag:i') }}
        </span>
        <span id="wxIcon2"></span>
        <span id="wxTemp"></span>
    </div>
</div>