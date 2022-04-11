/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$.widget( "custom.catcomplete", $.ui.autocomplete, {
  _create: function() {
    this._super();
    this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
  },
  _renderMenu: function( ul, items ) {
    var that = this,
      currentCategory = "";
    $.each( items, function( index, item ) {
      var li;
      if ( item.category != currentCategory ) {
        ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
        currentCategory = item.category;
      }
      li = that._renderItemData( ul, item );
      if ( item.category ) {
        li.attr( "aria-label", item.category + " : " + item.label );
      }
    });
  }
});


$(document).ready(function(){
    function omitirAcentos(text) {
      var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç";
      var original = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc";
      for (var i=0; i<acentos.length; i++) {
          text = text.replace(acentos.charAt(i), original.charAt(i));
      }
      return text;
    }
    
    /*Autocompletar buscador*/
    $('#txtQue').catcomplete({
      delay: 0,
      minLength: 2,
      source: urlQue,
      select: function(e, ui){
        $('#tq').val(ui.item.tq);
      },
      response: function(e,ui){   
        var comparar = '';
        var item = '';        
        var tq = 0;
        
        if(ui['content'][0]!==undefined){
          comparar = ui['content'][0]['comparar'];
          item = ui['content'][0]['value'];
          tq = ui['content'][0]['tq'];          
        }else{
          $('#tq').val(0);
        } 
        
        if(omitirAcentos($('#txtQue').val().toLowerCase())===comparar){
          $('#txtQue').val(item);
          $('#tq').val(tq);
        }else{
          $('#tq').val(0);
        } 
      },
      focus: function(e, ui ) {
        e.preventDefault();
        //$('#tq').val(ui.item.tq);
      }
    });
    
    $('#txtQue').keydown(function(e){
      if(e.keyCode===0 || e.keyCode===8 || e.keyCode===46){
        $('#tq').val(0);
      }
    });
    
    $('#txtEn').catcomplete({
      delay: 0,
      minLength: 2,
      source: urlEn,
      select: function(e, ui){
        $('#te').val(ui.item.te);
      },
      response: function(e,ui){        
        var comparar = '';
        var item = '';        
        var te = 0;
        
        if(ui['content'][0]!==undefined){
          comparar = ui['content'][0]['comparar'];
          item = ui['content'][0]['value'];
          te = ui['content'][0]['te'];
        }else{
          $('#te').val(0);
        }
        
        if(omitirAcentos($('#txtEn').val().toLowerCase())===comparar){
          $('#txtEn').val(item);
          $('#te').val(te);
        }else{
          $('#te').val(0);
        } 
      },
      focus: function(e, ui ) {
        e.preventDefault();
        //$('#te').val(ui.item.te);
      }
    });
    
    $('#txtEn').keydown(function(e){
      if(e.keyCode===0 || e.keyCode===8 || e.keyCode===46){
        $('#te').val(0);
      }
    });
    /*Fin Autocompletar buscador*/
    
});