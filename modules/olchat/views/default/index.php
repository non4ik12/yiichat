<style type="text/css">
    @-webkit-keyframes pulsate {
        50% {
            color: #000;
            text-shadow: 0 -1px rgba(0,0,0,.3), 0 0 5px #ffd, 0 0 8px #337ab7;
        }
    }
    @keyframes pulsate {
        50% {
            color: #000;
            text-shadow: 0 -1px rgba(0,0,0,.3), 0 0 5px #ffd, 0 0 8px #337ab7;
        }
    }
    .btn-blink {
        color: #337ab7;
        text-shadow: 0 -1px rgba(0,0,0,.1);
        -webkit-animation: pulsate 1.2s linear infinite;
        animation: pulsate 1.2s linear infinite;
    }
</style>
<div class="container">
    <div class="olchat-default-index row-fluid">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#online-chat">Dialogs</a>
            </li>
            <li><a data-toggle="tab" href="#users-online">Users online</a></li>
            <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
        </ul>
        <div class="tab-content">
            <div id="online-chat" class="tab-pane fade in active">
                <div id="dm-onchat">
                    <div class="dm-onchat-content row">
                        <div class="dm-onchat-body">
                            <div class="dm-onchat-contacts col-md-3">
                                <ul class="nav nav-pills nav-stacked">
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content dm-onchat-tabs">
                                </div>
                                <input type="text" class="dm-onchat-input" placeholder="Введите Ваше сообщение" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="users-online" class="tab-pane fade">
                <h3>Users online</h3>
                <div class="users-online-data"></div>
            </div>
            <div id="menu2" class="tab-pane fade">
                <h3>Menu 2</h3>
                <p>Some content in menu 2.</p>
            </div>
        </div>

    </div>
</div>
<script src="/js/olchatfulladmin.js"></script>
<script>$("#dm-onchat").olchatfulladmin();</script>