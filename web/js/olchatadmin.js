(function($) {
    $.widget('custom.olchatadmin', {
        userId: 0,
        storage: {
            userId: 'dmChatUserId',
            history: 'dmChatHistoriess'
        },
        childs: null,
        options: {
            server: "http://vsesvit.local:8008",
            classes: {
                head: "dm-onchat-head",
                field: "dm-onchat-input",
                content: "dm-onchat-content",
                body: "dm-onchat-body",
                dialog: "dm-onchat-dialog",
                close_block: "dm-onchat-close",
                close_btn: "dm-onchat-close span"
            }
        },
        _create: function() {
        	console.log("init");
            this.socket = io.connect(this.options.server);
            this._setProperties();
        },
        _setProperties: function() {
            var self = this,
                childs = [];
            this.socket.on('show chat', function() {
                self._showDialog();
            });
            this.socket.on('to moderator', function(msg) {
                self._appendNewMessage(msg);
            });
            this._getUid();
            $.each(this.options.classes, function(a, b) {
                childs[a] = $(self.element).find('.' + b);
            });
            this.childs = childs;
            this._on(this.childs.head, {
                click: "_showDialog"
            });
            this._on(this.childs.field, {
                keypress: "_sendMessage"
            });
            this._on(this.childs.close_btn, {
                click: "_hideDialog"
            });
        },
        _sendMessage: function(e) {
            if (e.which == 13) {
                var msg = this.childs.field.val();
                this.childs.head.hide();
                this.socket.emit('to moderator', msg);
                this._appendUserMsg(msg);
                this._toHistory({
                    sender: 'moderator',
                    message: msg
                });
                this.childs.field.val('');
            }
        },
        _showDialog: function() {
            var currentHistory = this._getHistory();
            if (currentHistory.length) {
                this.childs.head.hide();
            }
            this.childs.close_block.show();
            this.childs.body.fadeToggle(100);
            this._loadHistory();
        },
        _getHistory: function() {
            storageHistory = localStorage.getItem(this.storage.history);
            return (storageHistory) ? JSON.parse(storageHistory).currentHistory : [];
        },
        _hideDialog: function(e) {
            this.childs.head.show();
            this.childs.body.slideToggle(100);
            this.childs.close_block.hide();
        },
        _loadHistory: function() {
            var self = this,
                currentHistory = this._getHistory();
            if (currentHistory.length) {
                this.childs.head.hide();
                $.each(currentHistory, function(a, b) {
                    if (b.sender == 'user') {
                        self._appendUserMsg(b.message);
                    } else {
                        self._appendAdminMsg(b.message);
                    }
                });
            }
        },
        _appendNewMessage: function(msg) {
            this._appendAdminMsg(msg);
            this._toHistory({
                sender: 'user',
                message: msg
            });
        },
        _appendAdminMsg: function(msg) {
            this.childs.dialog.append($("<div />").addClass("message-row").append($("<div />").addClass("message user-message").text(msg)));
            this.childs.dialog.animate({
                scrollTop: this.childs.dialog.prop("scrollHeight") - this.childs.dialog.height()
            }, 20);
        },
        _appendUserMsg: function(msg) {
            this.childs.dialog.append($("<div />").addClass("message-row").append($("<div />").addClass("dm-onchat-photo")).append($("<div />").addClass("message admin-message").text(msg)));
            this.childs.dialog.animate({
                scrollTop: this.childs.dialog.prop("scrollHeight") - this.childs.dialog.height()
            }, 20);
        },
        _getUid: function() {
            this.userId = localStorage.getItem(this.storage.userId);
            if (!this.userId) {
                this.userId = this._generateUid();
                localStorage.setItem(this.storage.userId, this.userId);
            }
            this.socket.emit("userAuth", {
                uid: this.userId,
                page: window.location.href
            });
            return this.userId;
        },
        _generateUid: function() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        },
        _toHistory: function(data) {
            currentHistory = localStorage.getItem(this.storage.history);
            if (!currentHistory) {
                currentHistory = [];
            } else {
                currentHistory = JSON.parse(currentHistory).currentHistory;
            }
            currentHistory.push(data);
            localStorage.setItem(this.storage.history, JSON.stringify({
                currentHistory
            }));
        }
    })
})(jQuery);