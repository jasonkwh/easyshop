showsmallsearchbar = 0

$ ->
  if $(window).innerWidth() >= 768
    $('#categorycard').css "margin-left","-15px"
    $('#smallscreen').hide()
    if $('#profilemenu').length == 1
      $('#profiledropdownmenularge').css "left", $('#profilemenu').position().left - 25
    $('#merchantcontainer').css "top",275
    $('#merchantlogo').removeClass 'justify-content-center'
  else
    $('#photogallery').hide()
    $('#categorycarddisplay').hide()
    $('#largescreen').hide()
    $('#merchantcontainer').css "top",200
    $('#merchantlogo').addClass 'justify-content-center'
  $('#navbackground').css "height",$('.nav-pills').offset().top+$('.nav-pills').height()+4.5
  $('#photogallery').css "margin-top",(-($('#photogallery').offset().top-$('#navbackground').height()))
  if getParameterByName('login') != null
    if getParameterByName('login') == 'success'
      successlogindialog('登陸成功!')
  if getParameterByName('logout') != null
    if getParameterByName('logout') == 'success'
      successlogindialog('用戶已登出!')
  if getParameterByName('pagedelete') != null
    if getParameterByName('pagedelete') == 'success'
      successlogindialog('頁面已刪除!')
  if getParameterByName('pageadded') != null
    if getParameterByName('pageadded') == 'success'
      successlogindialog('已加入新頁面!')
    else
      errordialog('新增頁面失敗')
  if getParameterByName('adminbgupload') != null
    do openbgimgmanager
    if parseInt(getParameterByName('adminbgupload')) == 1
      successuploaddialog(getParameterByName('msg'))
    else
      errordialog(getParameterByName('msg'))
  $('#showsearchbar').on 'click', (event) ->
    if showsmallsearchbar == 0
      $('#smallsearchbar').show()
      showsmallsearchbar = 1
    else
      $('#smallsearchbar').hide()
      showsmallsearchbar = 0
    $('#navbackground').css "height",$('.nav-pills').offset().top+$('.nav-pills').height()+4.5
    return
  $('#showcategory').on 'click', (event) ->
    $('#categorymenu').modal()
    return
  $('#profilebtn').on 'click', (event) ->
    do logindialog
    return
  $('#profilebtn2').on 'click', (event) ->
    do logindialog
    return
  $('#newpage').on 'click', (event) ->
    do newpagedialog
    return
  $('#categorycard').on 'click', (event) ->
    if !$('#categorybody').is(':visible')
      $('#categorybody').show()
    else
      $('#categorybody').hide()
    return
  $('.logoutbtn').on 'click', (event) ->
    $.post('logout.php',
      logout: 'true').done (data) ->
        if data == 'success'
          setTimeout (->
            location.href = [
              location.protocol
              '//'
              location.host
              location.pathname
            ].join('') + '?id=' + getquerystringid() + '&logout=success'
            return
          ), 500
          do waitingdialog
        return
    return
  return

logindialog = ->
  username = ''
  password = ''
  checked = ''
  if isNaN parseInt localStorage.getItem 'morememberme'
    localStorage.setItem 'morememberme', 0;
  temprememberme = parseInt localStorage.getItem 'morememberme'
  if temprememberme == 1
    checked = 'checked'
    username = localStorage.getItem 'mousername'
    password = localStorage.getItem 'mopassword'
  inputaction = 'if (parseInt(localStorage.getItem(\'morememberme\')) == 1) { localStorage.setItem(\'mousername\', $(\'#username\').val()); localStorage.setItem(\'mopassword\', $(\'#password\').val()); localStorage.setItem(\'morememberme\', 1); }'
  changestatus = 'if (isNaN(parseInt(localStorage.getItem(\'morememberme\')))) { localStorage.setItem(\'morememberme\',0); } if (parseInt(localStorage.getItem(\'morememberme\')) == 0) { localStorage.setItem(\'mousername\', $(\'#username\').val()); localStorage.setItem(\'mopassword\', $(\'#password\').val()); localStorage.setItem(\'morememberme\', 1); } else { localStorage.removeItem(\'mousername\'); localStorage.removeItem(\'mopassword\'); localStorage.setItem(\'morememberme\', 0); }';
  content = '<div class="input-group" style="margin-bottom:10px"><input id="username" type="text" placeholder="用戶名或電子郵件地址" class="form-control input-login" aria-label="用戶名或電子郵件地址" value="' + username + '" onchange="' + inputaction + '" /><span class="input-group-addon input-login-addon"><i class="fa fa-user" aria-hidden="true"></i></span></div><div class="input-group" style="margin-bottom:10px"><input id="password" type="password" placeholder="密碼" class="form-control input-login" aria-label="密碼" value="' + password + '" onchange="' + inputaction + '" /><span class="input-group-addon input-login-addon"><i class="fa fa-lock" aria-hidden="true"></i></span></div><div class="row" style="margin-bottom:-5px"><div class="col-6"><div style="float:left;margin-left:5px" class="form-check abc-checkbox abc-checkbox-success abc-checkbox-circle"><input class="form-check-input" id="rememberme" type="checkbox" ' + checked + ' onclick="' + changestatus + '"><label class="form-check-label" for="rememberme">記住密碼</label></div></div><div class="col-6"><a style="float:right;color:#28a745" href="#">忘記密碼？</a></div></div>'
  $.confirm
    theme: 'modern'
    title: 'Hello'
    closeIcon: true
    icon: 'fa fa-user-circle'
    draggable: true
    content: content
    typeAnimated: true
    buttons:
      formSubmit:
        text: '<i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;登陸'
        btnClass: 'btn btn-success btn-nav btn-login'
        action: ->
          if ($('#username').val() == '') or ($('#username').val() == null)
            errorlogindialog('用戶名或電郵地址不能為空',0)
          else if ($('#password').val() == '') or ($('#password').val() == null)
            errorlogindialog('密碼不能為空',0)
          else
            $.post('login.php',
              username: $('#username').val()
              password: $('#password').val()).done (data) ->
                if data == 'failed'
                  errorlogindialog('用戶名或密碼不正確',0)
                else
                  setTimeout (->
                    location.href = [
                      location.protocol
                      '//'
                      location.host
                      location.pathname
                    ].join('') + '?id=' + getquerystringid() + '&login=success'
                    return
                  ), 500
                  do waitingdialog
                return
          return
  return

@waitingdialog = ->
  $.dialog
    theme: 'modern'
    title: '請稍等'
    icon: 'fa fa-refresh fa-spin'
    draggable: true
    content: ''
    typeAnimated: true
  return

successlogindialog = (msg) ->
  $.confirm
    icon: 'fa fa-check-square'
    title: msg
    content: '請點擊"確定"關閉該窗口'
    typeAnimated: true
    closeIcon: true
    buttons:
      closeBtn:
        text: '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;確定'
        btnClass: 'btn btn-success btn-nav btn-error'
        action:->
  return

successuploaddialog = (msg) ->
  $.confirm
    icon: 'fa fa-check-square'
    title: '上傳成功!'
    content: msg
    typeAnimated: true
    closeIcon: true
    buttons:
      closeBtn:
        text: '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;確定'
        btnClass: 'btn btn-success btn-nav btn-error'
        action:->
  return

errordialog = (msg) ->
  $.confirm
    icon: 'fa fa-warning'
    title: '錯誤!'
    content: msg
    typeAnimated: true
    closeIcon: true
    buttons:
      closeBtn:
        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;關閉'
        btnClass: 'btn btn-danger btn-nav btn-error'
        action:->
  return

errorlogindialog = (msg,action) ->
  $.confirm
    icon: 'fa fa-warning'
    title: '錯誤!'
    content: msg
    typeAnimated: true
    closeIcon: true
    buttons:
      tryAgain:
        text: '<i class="fa fa-repeat" aria-hidden="true"></i>&nbsp;重試'
        btnClass: 'btn btn-danger btn-nav btn-error'
        action: ->
          if action == 0
            do logindialog
          else if action == 1
            do newpagedialog
      closeBtn:
        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;取消'
        btnClass: 'btn btn-outline-danger btn-nav btn-error btn-error-cancel'
        action:->
  return

newpagedialog = ->
  $.confirm
    icon: 'fa fa-file-text'
    title: '新增頁面'
    content: '<div class="input-group"><input id="pagename" type="text" placeholder="請輸入頁面名稱" class="form-control input-login" aria-label="頁面名稱" /><span class="input-group-addon input-login-addon"><i class="fa fa-file-text" aria-hidden="true"></i></span></div>'
    typeAnimated: true
    closeIcon: true
    buttons:
      confirmBtn:
        text: '<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;新增'
        btnClass: 'btn btn-success btn-nav btn-error'
        action:->
          if $('#pagename').val() == '' or ($('#pagename').val() == null)
            errorlogindialog('頁面名稱不能為空',1)
          else
            $.post('newpage.php',
              newpage: 1
              newpagename: $('#pagename').val()).done (data) ->
                setTimeout (->
                  location.href = [
                    location.protocol
                    '//'
                    location.host
                    location.pathname
                  ].join('') + '?id=' + getquerystringid() + '&pageadded=' + data
                  return
                ), 500
                do waitingdialog
                return
      closeBtn:
        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;取消'
        btnClass: 'btn btn-outline-success btn-nav btn-error btn-success-cancel'
        action:->
  return

getParameterByName = (name, url) ->
  if !url
    url = window.location.href
  name = name.replace(/[\[\]]/g, '\\$&')
  regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)')
  results = regex.exec(url)
  if !results
    return null
  if !results[2]
    return ''
  decodeURIComponent results[2].replace(/\+/g, ' ')

@movepageup = (pageid,prevpageid) ->
  $.post('newpage.php',
    pageorderup: 1
    pageid: pageid
    prevpageid: prevpageid).done (data) ->
      if data == "success"
        setTimeout (->
          location.href = [
            location.protocol
            '//'
            location.host
            location.pathname
          ].join('') + '?id=' + getquerystringid()
          return
        ), 500
        do waitingdialog
      return
  return

@movepagedown = (pageid,nextpageid) ->
  $.post('newpage.php',
    pageorderdown: 1
    pageid: pageid
    nextpageid: nextpageid).done (data) ->
      if data == "success"
        setTimeout (->
          location.href = [
            location.protocol
            '//'
            location.host
            location.pathname
          ].join('') + '?id=' + getquerystringid()
          return
        ), 500
        do waitingdialog
      return
  return

@trashbgimg = (mobgimgid,typeid,merchantid) ->
  $.confirm
    icon: 'fa fa-warning'
    title: '刪除確認'
    content: '確認刪除該圖片?'
    typeAnimated: true
    closeIcon: true
    buttons:
      tryAgain:
        text: '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;確定'
        btnClass: 'btn btn-danger btn-nav btn-error'
        action: ->
          $.post('newpage.php',
            deletemobgimg: typeid
            mobgimgid: mobgimgid
            merchantid: merchantid).done (data) ->
              if data != "failed"
                successlogindialog('刪除成功')
                $('#mobg_'+mobgimgid).fadeOut 500
                if typeid == 1
                  do loadindexbgimg
                else if typeid == 2
                  if data != "success"
                    $('.mobgimghref img').removeClass 'mobgimgselected'
                    $('#mobgimg_' + String(data) + ' img').addClass 'mobgimgselected'
                    loadmerbgimg(merchantid)
              else
                errordialog('圖片刪除失敗')
              return
      closeBtn:
        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;取消'
        btnClass: 'btn btn-outline-danger btn-nav btn-error btn-error-cancel'
        action:->
  return

@trashpages = (pageid) ->
  $.confirm
    icon: 'fa fa-warning'
    title: '刪除確認'
    content: '確認刪除該頁面?'
    typeAnimated: true
    closeIcon: true
    buttons:
      tryAgain:
        text: '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;確定'
        btnClass: 'btn btn-danger btn-nav btn-error'
        action: ->
          $.post('newpage.php',
            trashpage: 1
            pageid: pageid).done (data) ->
              if data == "success"
                setTimeout (->
                  location.href = [
                    location.protocol
                    '//'
                    location.host
                    location.pathname
                  ].join('') + '?id=' + getquerystringid() + '&pagedelete=success'
                  return
                ), 500
                do waitingdialog
              else
                errordialog('頁面刪除失敗')
              return
      closeBtn:
        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;取消'
        btnClass: 'btn btn-outline-danger btn-nav btn-error btn-error-cancel'
        action:->
  return

@openbgimgmanager = ->
  $('#picManageModal').modal()
  setTimeout "$('.mobgimg').css('width',$('#addpicbtn').outerWidth()); $('.mobgimg').css('height',$('#addpicbtn').outerHeight());",250
  return

@loadindexbgimg = ->
  $.get 'getindexbg.php', (data) ->
    $('#bgCarousel').html data
    return
  return

@loadmerbgimg = (merchantid) ->
  $.get 'getmerbg.php?id=' + merchantid, (data) ->
    $(document.body).css
      'background-image': '-moz-linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.7),rgba(255,255,255,0.3),rgba(255,255,255,0),rgba(255,255,255,0)), url(' + data + ')'
      'background-image': '-webkit-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.7),rgba(255,255,255,0.3),rgba(255,255,255,0),rgba(255,255,255,0)), url(' + data + ')'
      'background-image': 'background-image: -webkit-linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.7),rgba(255,255,255,0.3),rgba(255,255,255,0),rgba(255,255,255,0)), url(' + data + ')'
      'background-image': '-o-linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.7),rgba(255,255,255,0.3),rgba(255,255,255,0),rgba(255,255,255,0)), url(' + data + ')'
      'background-image': 'linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.7),rgba(255,255,255,0.3),rgba(255,255,255,0),rgba(255,255,255,0)), url(' + data + ')'
    return
  return

getquerystringid = ->
  final_id = 0
  url = document.URL
  id_check = /[?&]id=([^&]+)/i
  match = id_check.exec(url)
  if match != null
    final_id = parseInt(match[1])
  else
    final_id = 0
  return final_id