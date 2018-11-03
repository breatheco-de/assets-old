<?php
    $tags = array(
        [ "value" => "html", "label" => "HTML" ],
        [ "value" => "css", "label" => "CSS" ],
        [ "value" => "php", "label" => "PHP" ],
        [ "value" => "reactjs", "label" => "Reactjs" ],
        [ "value" => "python", "label" => "Python" ],
        [ "value" => "before-after", "label" => "Before & After" ],
        [ "value" => "animated", "label" => "Animated GIF" ],
        [ "value" => "used-in-lesson", "label" => "Used in a lesson" ],
        [ "value" => "used-in-exercise", "label" => "Used in an exercise" ],
        [ "value" => "other", "label" => "Other" ]
    );
    $categories = array(
        [ "value" => "cheat-sheet", "label" => "Cheat Sheet" ],
        [ "value" => "photo", "label" => "Photo (from a camera)" ],
        [ "value" => "diagram", "label" => "Diagram Explanation (animated or not)" ],
        [ "value" => "ilustration", "label" => "Ilustration (handmaid)" ],
        [ "value" => "graphic", "label" => "Graphic (Screenshot, picture of code, etc.)" ],
        [ "value" => "meme", "label" => "Meme or joke" ]
    );
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Upload images</title>
        <script charset="utf-8" src="https://ucarecdn.com/libs/widget/3.6.1/uploadcare.full.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    </head>
    <body>
        <style type="text/css">
            .card-columns img:hover{
                cursor: pointer;
            }
            .filters{
                background: #BDBDBD;
                padding: 5px 0px;
            }
            .filters-label{
                padding-left: 20px;
                padding-top: 5px;
                padding-right: 20px;
                background: #BDBDBD;
                display: block;
            }
            .filter{
                display: inline-block;
                margin-right: 5px;
            }
            .card-body{
                padding: 5px;
            }
            .card-body p{
                margin: 0;
            }
            @media only screen and (min-width: 900px) {
                .card-columns {
                    column-count: 5;
                }
            }
            @media only screen and (min-width: 1300px) {
                .card-columns {
                    column-count: 6;
                }
            }
        </style>
        <div class="row filters">
            <span class="filters-label">Filters:</span>
            <select class="selectpicker filter category" data-param="category">
                <option value="">Select category...</option>
                <?php foreach($categories as $cat){ ?>
                    <option value="<?php echo $cat["value"]; ?>"><?php echo $cat["label"]; ?></option>
                <?php } ?>
            </select>
            <select class="selectpicker filter tag" data-param="tag">
                <option value="">Select tag...</option>
                <?php foreach($tags as $tag){ ?>
                    <option value="<?php echo $tag["value"]; ?>"><?php echo $tag["label"]; ?></option>
                <?php } ?>
            </select>
            <button class="btn btn-primary" onClick="openDialog();">Upload</button>
        </div>
        <div id="url-modal" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                    <h5>Please describe your image further:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              <div class="modal-body">
                <div id="first-step" class="step">
                    <div id="errors" class="alert alert-danger d-none"><ul></ul></div>
                    <form>
                      <div class="form-group">
                        <label class="b-block" for="exampleInputEmail1">Tags</label>
                        <div>
                            <select id="img-tags" class="selectpicker form-control" multiple>
                                <?php foreach($tags as $tag){ ?>
                                    <option value="<?php echo $tag["value"]; ?>"><?php echo $tag["label"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Category</label>
                        <div>
                            <select id="img-category" class="selectpicker form-control">
                                <option value="select">Select...</option>
                                <?php foreach($categories as $cat){ ?>
                                    <option value="<?php echo $cat["value"]; ?>"><?php echo $cat["label"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Description</label>
                        <div>
                            <textarea id="img-description" class="form-control"></textarea>
                        </div>
                      </div>
                    </form>
                </div>
                <div id="second-step" class="d-none step">
                    <p>Here is your image url:</p>
                    <div class="input-group mb-3">
                        <input id="img-url" type="text" class="form-control" onClick="selectText(this);" />
                        <div class="input-group-append copybtn" data-toggle="tooltip" title="">
                            <button class="input-group-text btn" onClick="copyText(this);">copy</button>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onClick="processForm();">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div id="card-click-modal" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <p>Here is your image url:</p>
                <div class="input-group mb-3">
                    <input id="img-click-url" type="text" class="form-control" onClick="selectText(this);" />
                    <div class="input-group-append copybtn" data-toggle="tooltip" title="">
                        <button class="input-group-text btn" onClick="copyText(this);">copy</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <div>
                        <textarea id="img-click-description" class="form-control" readonly></textarea>
                    </div>
                </div>
                <button class="btn mt-0" onClick="confirmDeletion(this);"><i class="fas fa-trash-alt"></i> delete</button>
              </div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
        
            function updateGallery(){
                let query = {
                    tag: $('.filter .tag').val(),
                    category: $('.filter .category').val()
                };
                getImages(query).then(images => {
                    $('.card-columns').html(images.map(img => renderImage(img)));
                    $('.card-columns img').click(e => {
                        $('#img-click-url').val(e.target.src);
                        $('#img-click-description').val(e.target.alt);
                        $('#card-click-modal').modal();
                    });
                });
            }
            window.onload = function(){
                updateGallery();
                
                $('.filter').change(() => updateGallery());
            }
            
            function confirmDeletion(deleteBtn){
                const input = $(deleteBtn).closest(".modal-body").find("input");
                bootbox.confirm({
                    message: "Are you sure you want to delete this email?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            deleteImage(input[0].value)
                                .then(()=>updateGallery())
                                .catch(err => console.error(err));
                            $('.modal').modal('hide');
                        } 
                    }
                });
            }
            function selectText(input){
                $(input).select();
            }
            function copyText(copyBtn){
                const input = $(copyBtn).closest(".input-group").find("input");
                input.select();
                document.execCommand("copy");
                $('.copybtn').attr('title','URL has been copied!').tooltip('show');
                setTimeout(() => $('.copybtn').tooltip('dispose'), 1000);
            }
            function processForm(){
                $('#errors').addClass('d-none');
                
                var errors = [];
                var tags = $('#img-tags').val();
                if(tags == '') errors.push('Tags are empty');
                var category = $('#img-category').val();
                if(category == '' || category == 'select') errors.push('The category is empty');
                var description = $('#img-description').val();
                if(description == '') errors.push('The description is empty');
                var url = $('#img-url').val();
                if(url == '') errors.push('The URL is empty');

                var image = {
                    tags: tags.join(""),
                    category: category,
                    description: description,
                    url: url
                };
                
                if(errors.length == 0) addImage(image).then(function(){
                    $('#errors').removeClass('d-none');
                    $('#errors').removeClass('alert-danger');
                    $('#errors').addClass('alert-success');
                    $('#errors').html('The image was uploaded successfully!');
                    
                    $('.step').addClass('d-none');
                    $('#second-step').removeClass('d-none');
                    $('#img-url').select();
                    
                    $('.modal-footer').addClass('d-none');
                    
                    let query = {
                        tag: $('.filter .tag').val(),
                        category: $('.filter .category').val()
                    };
                    getImages(query).then(images => {
                        $('.card-columns').html(images.map(img => renderImage(img)));
                        $('.card-columns img').click(e => {
                            $('#img-click-url').val(e.target.src);
                            $('#img-click-description').val(e.target.alt);
                            $('#card-click-modal').modal();
                        });
                    });
                }).catch(function(error){
                    $('#errors').html(error.message || error.msg || error);
                    $('#errors').removeClass('d-none');
                });
                else{
                    $('#errors').html(errors.map(function(error){ return "<li>"+error+"</li>"; }).join(''));
                    $('#errors').removeClass('d-none');
                }
            }
            function addImage(image){
                return new Promise(function(resolve, reject){
                    fetch('../image', {
                        method: 'post',
                        headers: {
                            "Content-Type": "application/json; charset=utf-8"
                        },
                        body: JSON.stringify(image)
                    })
                    .then(function(resp){
                        if(resp.status == 400) resp.json().then(data => reject(data.msg));
                        return resp.json();
                    })
                    .then(json => resolve())
                    .catch(error => reject(error));
                });
            }
            function deleteImage(imageUrl){
                const matches = imageUrl.match(/https:\/\/ucarecdn\.com\/(.*)\/.*$/);
                if(matches){
                    return new Promise(function(resolve, reject){
                        fetch('../image/'+matches[1], {
                            method: 'delete'
                        })
                        .then(function(resp){
                            if(resp.status == 400) resp.json().then(data => reject(data.msg));
                            return resp.json();
                        })
                        .then(json => resolve())
                        .catch(error => reject(error));
                    });
                }
            }
            function getImages(args=''){
                return new Promise(function(resolve, reject){
                    return fetch('../image/all?'+$.param( args ))
                    .then(function(resp){
                        if(resp.status > 199 && resp.status < 400) return resp.json();
                        else reject("The request could not be completed");
                    })
                    .then(data => resolve(data))
                    .catch(error => reject(error));
                });
            }
            function renderImage(img){
                return `<div class="card img" data-src="${img.url}">
                    <img class="card-img-top" src="${img.url}" alt="${img.description}">
                    <div class="card-body">
                      <p>${img.category}</p>
                      <p>${img.tags.split(",").map(tag => `<span class="badge badge-secondary">${tag}</span>`).join("")}</p>
                    </div>
                </div>`;
            }
            function openDialog(){
                $('.step').addClass('d-none');
                $('#first-step').removeClass('d-none');
                $('form input').val('');
                $('.modal-footer').removeClass('d-none');
                $('.selectpicker').selectpicker('val','');
                $('form textarea').val('');
                $('.alert').addClass('d-none');
                uploadcare.openDialog(null, {
                    publicKey: '87bbef8b86f6973ccade',
                    previewStep: true,
                    imageShrink: '1024x1024',
                    imagesOnly: true
                }).done(function(file) {
                    file.promise().done(function(fileInfo){
                        $('#img-url').val(fileInfo.cdnUrl);
                        $('#url-modal').modal();
                    });
                });
            }
        </script>
        
        <div class="contaner-fluid">
            <div class="card-columns">
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    </body>
</html>