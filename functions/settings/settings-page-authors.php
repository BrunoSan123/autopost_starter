
<!-- Content for section Authors -->
 <?php 
 
 require_once(dirname(__FILE__).'/../database_func.php');
 ?>

        <div class="tab-content" id="tab-authors" class="settings-field" style="display: none;">
		
		<div class="tab-content-card">
            <h3><?php _e('Manage your authors','autopost')?></h3>
            <b><?php _e('Create, remove, define the default authors','autopost')?></b>
        </div>
		
		<br>
					
		<div class="tab-content-card">
				<div><b><?php _e('Actions','autopost')?></b>
				</div>
				
				<div class="authors-container">
					<div class="authors-container1">
						<button id="" onclick="delete_user_bulk()" class="authors-button authors-button-remove"><?php _e('Remove','autopost')?></button>
					</div>
					<div class="authors-container2">
						<button id="" onclick="()" class="authors-button-add"><?php _e('Add new author +','autopost')?></button>
					</div>
						
			</div>

				<div class="authors-container-table">

<!-- Content for Authors List-->				
				<div class="wrap">
					<table id="tabelaAutores" class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><input type="checkbox" style="margin: 0px !important;" id="toggle_author_all" name="toggle_author_all" value="1"></th>
								<th><?php _e('Nome do Autor','autopost')?></th>
								<th><?php _e('Email','autopost')?></th>
								<th><?php _e('Artigos Escritos','autopost')?></th>
								<th><?php _e('Ativar/Desativar','autopost')?></th>
								<th><?php _e('Ações','autopost')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							
							$authors = get_users(['role' => 'author']);

							foreach ($authors as $author) {
								$post_count = count_user_posts($author->ID);
								?>
								<tr class="author_element">
									<td><input type="checkbox" class="author-checkbox" name="selected_authors[]" value="<?php echo $author->ID; ?>"></td>
									<td class="author" data-id="<?php echo $author->ID?>"><?php echo $author->display_name; ?></td>
									<td class="author_email"><?php echo $author->user_email; ?></td>
									<td class="article"><?php echo $post_count; ?></td>
									<td>
										<label class="switch">
                      <?php $user_data=retriveUserData($author->display_name)?>
											<input id="ToggleAutor" type="checkbox" name="switch_toggle_author_<?php echo $author->ID; ?>" 
													onchange="setDefaultAuthor(this)"
											value="1" <?php 
                      
                            if(empty($user_data)){
                              echo '';
                            }elseif ($user_data[0]->active==1) {
                              echo 'checked';
                            }elseif($user_data[0]->active==0){
                              echo '';
                            }
                      ?>>
											<span class="slider round"></span>
										</label>
									<input style="display: none;" type="checkbox" name="toggle_author_<?php echo $author->ID; ?>" value="1" <?php echo get_user_meta($author->ID, 'author_status', true) == 1 ? 'checked' : ''; ?>></td>
									
									
									<td><button class="edit-author-button" data-author-id="<?php echo $author->ID; ?>">&#xFE19;</button></td>
								
									<td>
										<button class="remove-author-button" data-author-id="<?php echo $author->ID; ?>"
												onclick="removeAuthor(<?php echo $author->ID; ?>)">&#10006;</button>
									</td>
								</tr>

								<?php
							}
							

							?>
							


<script>
// SCRIPT REMOVE AUTHOR



</script>



<script>
// SCRIPT AUTHORS - CAIXA AÇÕES (custom dialog box)
    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('.edit-author-button');

        editButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.stopPropagation(); 

                var authorId = button.getAttribute('data-author-id');
                var existingDialog = document.querySelector('.custom-dialog-box');
                const author=document.querySelector(".author")
                const email=document.querySelector(".author_email")
                console.log(author.textContent,email.textContent)
                if (existingDialog) {
                    document.body.removeChild(existingDialog);
                }

                // Criar a caixa de diálogo
                var dialogBox = document.createElement('div');
                dialogBox.className = 'custom-dialog-box';
                dialogBox.innerHTML = `
					<button class="custom-dialog-button" onclick="setDefaultAuthor(${authorId})">Define as Default</button>
                    <button id="authors-button-edit2" class="custom-dialog-button edit-author-modal" onclick="editAuthor(${authorId},'${author.textContent}','${email.textContent}')">Edit</button>
                    <button id="authors-button-remove2" class="custom-dialog-button remove-author-modal" style="color: #D33535"onclick="deleteAuthor(${authorId})">Delete</button>
                `;

                // Posicionar a caixa de diálogo sobre o botão usando as coordenadas do evento de clique
                dialogBox.style.position = 'absolute';
                dialogBox.style.top = event.clientY + 'px';
                dialogBox.style.left = event.clientX + 'px';
                
                document.body.appendChild(dialogBox);

                document.addEventListener('click', closeDialogBox);
            });
        });
    });

   async function setDefaultAuthor(link) {
        const author=document.querySelector(".author")
        const article=document.querySelector(".article")
        console.log(author.textContent);
        console.log(article.textContent);
        const insert = await fetch('/wp-json/custom/v1/user_status',{
          method:'POST',
          body:JSON.stringify({author:author.textContent,articles:parseInt(article.textContent),active:link.checked}),
          headers:{'Content-Type':'application/json'}

        })
        // Adicionar lógica para definir como padrão (ativar a checkbox) aqui
        const response=insert.json();
        console.log(response);  
        closeDialogBox();
    }

   function editAuthor(authorId,authorName,authorEmail) {
        // Adicionar lógica de edição aqui
        console.log('Editar autor com ID ' + authorId);
        var modaledit = document.getElementById('editAuthorModal');
        var author_name=document.getElementById("author-name");
        var user_id= document.getElementById('user_id')
        var author_email= document.getElementById("author-email")

        modaledit.style.display = 'block';
        author_name.value=authorName;
        author_email.value=authorEmail;
        user_id.value=authorId;
        const modalsubmit=document.getElementById("submitedit")
        modalsubmit.addEventListener('click',async()=>{
          const update =await fetch('/wp-json/custom/v1/update_user',{
          method:'PUT',
          body:JSON.stringify({user_id:authorId,user_name:author_name.value,user_email:author_email.value}),
          headers:{'Content-Type':'application/json'}
        })
        const response=update.json();
        if(response.ok){
          location.reload()
        }else{
          alert(response.data);
        }

        })

        closeDialogBox();
    }

    async function deleteAuthor(authorId) {
        // Adicionar lógica de exclusão aqui
        console.log('Deletar autor com ID ' + authorId);
        const remove_user=await fetch('/wp-json/custom/v1/delete_user',{
          method:'DELETE',
          body:JSON.stringify({user_id:authorId}),
          headers:{"Content-Type":"application/json"}
        })
        const response=remove_user.json();
        if(remove_user.ok){
          location.reload();
        }else{
          alert(remove_user.data.message);
        }
        closeDialogBox();
    }

    function closeDialogBox() {
        var dialogBox = document.querySelector('.custom-dialog-box');
        if (dialogBox) {
            document.body.removeChild(dialogBox);
            document.removeEventListener('click', closeDialogBox);
        }
    }
</script>


							</tbody>
						</table>
					</div>
				</div>
		</div>

		<br>
		
<!-- Content for Authors List - END -->	
		<div class="backdrop-bottom">
		</div>
		

<!-- End of page authors-->	


<!--modais de Add/editar/remover/ INICIO-->
<!-- Modal para ADD New autor -->
<div id="addAuthorModal" class="modal">
  <div class="modal-content">
    
		<div class="modal-header">Add new author
		</div>
	
		<div class="modal-inputs">
	
				<div class="lb">
					<label for="" class="settings-subitem"><?php _e('Name','autopost')?></label><br>
						<input class="input-author" type="text" id="add-author-name" name="modal-inputs"  placeholder="Ex: John Doe">
				</div>
				<div class="lb">
					<label for="" class="settings-subitem"><?php _e('Email','autopost')?></label><br>
						<input class="input-author" type="text" id="add-author-email" name="modal-inputs" value="" placeholder="Ex: john@doe.com">
				</div>
		</div>
		<div class="modal-switch">

				<div class="">
		 			<label for="" class="settings-subitem"><?php _e('Define as Default','autopost')?></label>
				</div>
				<div class="">
					<label class="switch defineDefaultButton" >
						<input id="modalswitchdefault" type="checkbox">
						<span class="slider round"></span>
				</div>
		</div>
		
		<div class="modalbuttons ">
			<div><span class="close closecancel"  onclick="" class="submit-button cancelbutton"><?php _e('Cancel','autopost')?></span></div>
			<div><button id="ken" onclick="" class="submit-button applybutton submitmodal"><?php _e('Add New Author','autopost')?></button></div>
		</div>    
  </div>
</div>
<!-- Modal para ADD New autor FIM-->

<!--Modal Edit author INICIO-->

<div id="editAuthorModal" class="modal">
  <div class="modal-content">
    
		<div class="modal-header"><?php _e('Edit author','autopost')?>
		</div>
		
		<div class="modal-inputs">

        <input type="hidden" name="user_id" id="user_id">
    
				<div class="lb">
					<label for="" class="settings-subitem"><?php _e('Name','autopost')?></label><br>
						<input class="input-author" type="text" id="author-name" name="name" value="" placeholder="Ex: John Doe">
				</div>
				<div class="lb">
					<label for="" class="settings-subitem"><?php _e('Email','autopost') ?></label><br>
						<input class="input-author" type="text" id="author-email" name="email" value="" placeholder="Ex: john@doe.com">
				</div>
		</div>
		<div class="modal-switch">
				<div class="">
	
					<label for="" class="settings-subitem"><?php _e('Define as Default','autopost')?></label>
				</div>
				<div class="">
					<label class="switch defineDefaultButton" >
						<input id="modalswitchdefault" type="checkbox">
						<span class="slider round"></span>
				</div>
		</div>
		
		<div class="modalbuttons">
			<div><button class="closeedit closecancel"  onclick="close_modal()" class="submit-button cancelbutton"><?php _e('Cancel','autopost') ?></button></div>
	
			<div><button id="submitedit" name="submitmodal" onclick="" class="submit-button submitmodal"><?php _e('Save Changes','autopost')?></button></div>
  </div>
    
  </div>
</div>
<!--Modal edit author fim-->
<!--Modal REMOVE author INICIO-->

<div id="removeAuthorModal" class="modal">
  <div class="modal-content">

		<div class="modal-header"><?php _e('Remove Author','autopost')?>
		</div>
		<div class="modal-inputs">
			<label id="removelabel" for="" class="settings-subitem"><?php _e('Are you sure you want to remove this author? This action can’t be undone','autopost')?></label>
		</div>
		
			<div class="modalbuttons">
				<div><button class="closeremove"  onclick="" class="submit-button cancelbutton"><?php _e('Cancel','autopost')?></button></div>
				<div><button id="modalremovebutton" onclick="" class="submit-button"><?php _e('Remove','autopost')?></button></div>
			</div>
    
		</div>
	</div>
</div>
<!--Modal REMOVE author FIM-->

<!--modais de Add/editar/remover/ FIM-->

<!--SCRIPTS DE Add/editar/remover/ INICIO-->


<script>
// MODAL ADICIONAR AUTOR - EDITAR - REMOVER - INÍCIO


  document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('addAuthorModal');
	var closeButton = document.querySelector('.close');
	
	document.querySelector('.authors-button-add').addEventListener('click', function () {
      modal.style.display = 'block';
    });
	
    closeButton.addEventListener('click', function () {
      modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    });
	
//-------------// Selecionar o modal ADD AUTHOR - FIM

//-------------// Selecionar o modal EDIT AUTHOR - INÍCIO

    var modaledit = document.getElementById('editAuthorModal');
    var closeeditButton = document.querySelector('.closeedit');
    document.querySelector('.authors-button-edit').addEventListener('click', function () {
      modaledit.style.display = 'block';
    });
	
    closeeditButton.addEventListener('click', function () {
      modaledit.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
      if (event.target == modaledit) {
        modaledit.style.display = 'none';
      }
    });

	
//-------------// Selecionar o modal EDIT AUTHOR - INÍCIO

//-------------// Selecionar o modal REMOVE AUTHOR - INÍCIO
    var modalremove = document.getElementById('removeAuthorModal');
    var closeremoveButton = document.querySelector('.closeremove');
	
    document.querySelector('.authors-button-remove').addEventListener('click', function () {
      modalremove.style.display = 'block';
    });	
	
    closeremoveButton.addEventListener('click', function () {
      modalremove.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
      if (event.target == modalremove) {
        modalremove.style.display = 'none';
      }
    });

//-------------// Selecionar o modal REMOVE AUTHOR - FIM

	
  });
  // MODAL ADICIONAR AUTOR - EDITAR - REMOVER - FIM
</script>

<!--SCRIPTS DE Add/editar/remover/ FIM-->

<!--SCRIPTS DE ADICIONAR AUTOR INÍCIO-->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('ken').addEventListener('click', function () {
            var authorName = document.getElementById('add-author-name').value;
            var authorEmail = document.getElementById('add-author-email').value;

            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var data = {
                'action': 'add_new_author',
                'author_name': authorName,
                'author_email': authorEmail
            };

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data),
            })
            .then(response => response.text())
            .then(response => {
                console.log(response);
				
				document.getElementById('addAuthorModal').style.display = 'none';

			location.reload();

            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

</script>
<!--SCRIPTS DE  ADICIONAR AUTOR FIM-->

<script>
// TABS Hide all tab content except the first one

        jQuery(document).ready(function($) {
            // Hide all tab content except the first one
            $('.tab-content:not(:first)').hide();

            // Handle tab navigation click event
            $('.tab-style-button').click(function() {
                $('.tab-style-button').removeClass('tab-active');
                $(this).addClass('tab-active');
                var tabId = $(this).index();
                $('.tab-content').hide();
                $('.tab-content:eq(' + tabId + ')').show();
            });
        });


        function close_modal(){
          const authorEdit=document.getElementById("editAuthorModal")
          authorEdit.style.display='none';

        }
    </script>

    <script>
      function delete_user_bulk(){
        const authorsElements=document.querySelectorAll(".author_element");
        const userCheck=document.querySelectorAll(".author-checkbox")
        const author=document.querySelectorAll(".author")

        authorsElements.forEach((e,i)=>{
          if(userCheck[i].checked){
            deleteAuthor(author[i].getAttribute("data-id"));
          }
        
        })
      }
    </script>
    