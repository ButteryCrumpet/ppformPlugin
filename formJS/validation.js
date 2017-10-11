(function($){
  $.fn.autoValid = function(settings) {
    var validation = new AutoValidation(this, settings);
  };
  //checks etc

  function AutoValidation(element, settings) {
    var _ = this;

    //settings //properly intergrate settings
    _.settings = {
      formAttr: 'ppform',
      validTypeAttr: 'ppform',
      errorFieldAttr: 'data-error',
      includeBase: false,
      errorClass: 'hasError',
      validClass: 'valid',
      errorMessage: 'は不正です',
    }
    $.extend(_.settings, settings);

    //'private' vars
    _.baseForm = element;
    _.validators = new Validators();
    _.fields = [];
    _.formValid = false;

    _.getFieldElements();
    _.setListeners();
    
  }

  AutoValidation.prototype.setListeners = function() {
    var _ = this;

    _.baseForm.on('fieldValid', function(){
      _.checkFormValid();
    })
    _.baseForm.on('fieldInvalid', function(){
      _.checkFormValid();
    })
    _.baseForm.on('formValid', function(){
      _.onValid();
    })
    _.baseForm.on('formInvalid', function(){
      _.onInvalid();
    })
    
  }

  AutoValidation.prototype.getFieldErrorElements = function(name) {
    var _ = this;
    return _.baseForm.find('['+ _.settings.errorFieldAttr +'='+name+']')
  }

  AutoValidation.prototype.getFieldElements = function() {
    var _ = this;

    _.baseForm.find('[data-ppform]').each(function(){
      var ele = $(this);
      var name = ele.attr('name');
      var errorElements = _.getFieldErrorElements(name);
      var vType = ele.data(_.settings.validTypeAttr);
      var required = !ele.prop('required') ? false : true;
      var obj = new ValidationField(name, ele, vType, required, errorElements);

      _.fields.push(obj);

      ele.on('change paste keyup', utils.debounce(function() {
        _.validate(obj);
      }, 300))
      
    })
  }

  AutoValidation.prototype.validate = function(vField) {
    var _ = this;

    console.log('Validating');

    if (vField.element.val() === '') {
      vField.element.removeClass(_.settings.errorClass +' '+ _.settings.validClass);
      vField.errElement.empty();
      return;
    }

    valid = _.validators.validateByType(vField.vType, vField.element.val());
    if (!valid) {
      vField.element.removeClass(_.settings.validClass);
      vField.element.addClass(_.settings.errorClass);
      vField.errElement.text(vField.name + _.settings.errorMessage);
      vField.valid = false;
      _.baseForm.trigger({
        type: 'fieldInvalid',
        field: vField,
      });
    } else {
      vField.element.removeClass(_.settings.errorClass);
      vField.element.addClass(_.settings.validClass);
      vField.errElement.empty();
      vField.valid = true;
      _.baseForm.trigger({
        type: 'fieldValid',
        field: vField,
      });
    }
  }

  AutoValidation.prototype.checkFormValid = function() {
    var _ = this;

    for (var i = 0; i < _.fields.length; i++) {
      if (!_.fields[i].valid && _.fields[i].required) {
        if (_.formValid) {
          _.baseForm.trigger('formInvalid');
        }
        return;
      }
    }

    if (!_.formValid) {
      _.baseForm.trigger('formValid');
    }
  }

  AutoValidation.prototype.onValid = function() {
    var _ = this;

    _.formValid = true;
    console.log('form is valid');
  }

  AutoValidation.prototype.onInvalid = function() {
    var _ = this;

    _.formValid = false;
    console.log('form invalid');
  }

  //FIELD STRUCTishthing//
  function ValidationField(name, element, vType, required, errElement) {
    this.name = name;
    this.element = element;
    this.vType = vType;
    this.required = required;
    this.errElement = errElement;
    this.valid = false;
  }

  //VALIDATOR CLASS//
  //probably needs a better implementation, or jam it in utils?
  function Validators() {
    var _ = this;

    _.validators = {
      kana: _.kana, 
      jchars: _.japaneseChars, 
      phone: _.phoneNo, 
      zip: _.japanZip, 
      url: _.URL,
      email: _.email,
    };
  }

  Validators.prototype.validateByType = function(vType, data) {
    var _ = this;

    if (_.validators[vType] === undefined) {
      //console.log('nonvalidation')
      return true;
    }

    var func = _.validators[vType];
    return func(data);
  }

  Validators.prototype.kana = function(data) {
    var regex = /^([゠ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンヴヵヶヷヸヹヺ・ーヽヾヿ]+)$/
    return regex.test(data);
  }

  //may need updating often, confirm utf chars
  Validators.prototype.japaneseChars = function(data) {
    var regex = /^([\u3000-\u303F\u3040-\u309F\u30A0-\u30FF\uFF00-\uFFEF\u4E00-\u9FAF]+)$/mg;
    return regex.test(data);
  }

  Validators.prototype.phoneNo = function(data) {
    var regex = /^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/;
    return regex.test(data);
  }

  Validators.prototype.japanZip = function(data) {
    var regex = /^([0-9]){3}-?([0-9]){4}$/;
    return regex.test(data);
  }

  Validators.prototype.URL = function(data) {
    var regex = /\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i;
    return regex.test(data);
  }

  //weak client side validation
  Validators.prototype.email = function(data) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(data);
  }

})(jQuery);