<div class="container">
    <div class="menu col-md-3">
        <?=$this->render('../left_menu')?>
    </div>
    <div class="olchat-default-index col-md-9">
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
</div>
<script src="/js/olchatfulladmin.js"></script>
<script>$("#dm-onchat").olchatfulladmin();</script>