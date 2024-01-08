// creates a modal component

function Modal(title, content) {
    this.title = title;
    this.content = content;

    // parseDOM should be a globally access thing
    this.parseDOM();
    this.create();
};

Modal.prototype.parseDOM = function() {
    this.body =  jQuery('body');
};

Modal.prototype.create = function()
{
    this.maskEl = document.createElement('div');
    this.maskEl.id = 'modal-mask';

    this.containerEl = document.createElement('div');
    this.containerEl.className = 'modal-container';

    this.titleEl = document.createElement('div');
    this.titleEl.className = 'modal-title';
    this.titleEl.innerHTML = this.title;

    this.contentEl = document.createElement('div');
    this.contentEl.className = 'modal-content';

    this.containerEl.addEventListener('keypress', function (e) {
        var key = e.which || e.keyCode;
        if (key === 13 && this.submitAction) { // enter key
            this.executeSubmit();
        }
    }.bind(this));

    this.toolbarEl = document.createElement('div');
    this.toolbarEl.className = 'modal-toolbar';

    this.submitButton = document.createElement('button');
    this.submitButton.className = 'btn push-right';
    this.submitButton.innerText = 'OK';

    this.cancelButton = document.createElement('button');
    this.cancelButton.className = 'btn btn-grey';
    this.cancelButton.innerText = 'Cancel';
    this.setCancelAction();

    this.toolbarEl.appendChild(this.submitButton);
    this.toolbarEl.appendChild(this.cancelButton);

    jQuery(this.containerEl).append(this.titleEl);
    jQuery(this.containerEl).append(this.contentEl);
    jQuery(this.containerEl).append(this.toolbarEl);

    // add dynamically created DOM elements to body
    this.body.prepend(this.containerEl);
    this.body.prepend(this.maskEl);

    this.resize();

    this.maskEl.addEventListener('click', function(e) {
        this.destroy();
    }.bind(this));
};

Modal.prototype.resize = function() {
    jQuery(this.containerEl).css("top", 0.5 * $(window).height() - (jQuery(this.containerEl).height() / 2));
};

Modal.prototype.setVisible = function() {
    jQuery(this.maskEl).fadeIn(100);
    jQuery(this.containerEl).fadeIn(100);
};

Modal.prototype.destroy = function() {
    this.maskEl.remove();
    this.containerEl.remove();
};

Modal.prototype.setTitle = function(title) {
    this.title = title;
    this.titleEl.innerHTML = title;
};

Modal.prototype.setVue = function(shorthand) {
    this.content = shorthand;
    this.contentEl.innerHTML = shorthand;

    const content = new Vue({
        el: '.modal-content',
        data: {
            message: 'message here'
        }
    });
    this.resize();
};

/*
* For plain text or html
* */
Modal.prototype.setContent = function(content) {
    this.content = content;
    this.contentEl.innerHTML = content;
    this.resize();
};

Modal.prototype.setSubmitAction = function(action) {
    this.submitAction = action;
    this.submitButton.addEventListener('click', function(e) {
        this.executeSubmit();
    }.bind(this));
};

Modal.prototype.executeSubmit = function() {
    this.submitAction();
};

Modal.prototype.setCancelAction = function() {
    this.cancelButton.addEventListener('click', function(e) {
        this.destroy();
    }.bind(this));
};

Modal.prototype.setData = function(data) {
    this.data = data;
};

Modal.prototype.getElement = function(data) {
    return this.containerEl;
};


