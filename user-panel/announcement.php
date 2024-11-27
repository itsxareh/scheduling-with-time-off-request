
<style>
.announce {
    display: flex;
    justify-content: center;
}
.titles {
    font-size: 6.1vw;
}
.descrip {
    font-size: 4vw;
}
.author {
    font-size: 3vw;
}
.chat-box{
    position: relative;
    min-height: 0px;
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
    background: #f7f7f7;
    box-shadow: inset 0 32px 32px -32px rgb(0 0 0 / 5%),
                inset 0 -32px 32px -32px rgb(0 0 0 / 5%);
}
.chat-box::-webkit-scrollbar {
    width: 0%;
}
.chat-box .chat{
  margin: 15px 0;
}
.chat-box .chat p{
  word-wrap: break-word;
  padding: 8px 16px;
  box-shadow: 0 0 32px rgb(0 0 0 / 8%),
              0rem 16px 16px -16px rgb(0 0 0 / 10%);
}
.chat-box .outgoing{
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}
.outgoing .user i{
  margin-right: 8px;
}
.chat-box .outgoing .details{
  margin-left: auto;
  max-width: calc(100% - 130px);
}
.outgoing .details p{
  background: #333;
  color: #fff;
  border-radius: 18px 18px 0 18px;
}
.chat-box .incoming{
  display: flex;
  flex-direction: column;
  align-items:flex-start;
}
.incoming .user i{
  margin-left: 8px;
}
.chat-box .incoming .details{
  margin-right: auto;
  max-width: calc(100% - 130px);
}
.incoming .details p{
  background: #fff;
  color: #333;
  border-radius: 18px 18px 18px 0;
}
#comment_btn:hover {
  background: none;
}
</style>
<div class="container-fluid ps-0 announce">   
	    <div class="col-lg-12 ps-0">
			<div class="card">
                <div class="card-header">
                    <b>Announcement</b>
                </div>
                <div class="card-body">
                <?php 
                    $staff =  $conn->query("SELECT * from announcement where id = '1' ");
                    while($row = $staff->fetch_assoc()):
				?>
                    <p class="text-7xl p-4 titles"><?= $row['title']?></p>
                    <p class="text-4xl p-3 descrip"><?= nl2br($row['description'])?></p>
                    <p class="text-3xl text-end p-2 author"><b><?= $row['author']?></b></p>
                    <?php endwhile?>
                </div>
            </div>
            <div class="col-lg-4 p-0 mt-10 float-right">
                <div class="card">
                    <div class="card-header">
                        <p class="">Comments</p>
                    </div>
                    <div class="card-body">
                        <div class="chat-box">

                        </div>
                    </div>
                    <div class="card-footer" style=" padding-bottom: 0px;">
                        <form action="" id="comment_form">
                            <div class="form-group" style="display:flex; ">
                                <input type="text" name="comment" id="comment" autocomplete="off" placeholder="Say something..." style="padding: 0px 5px; width: 100%; margin-right: 5px;"><button class="comment_btn" id="comment_btn" style=" font-size: 20px;"><span class="fa fa-arrow-alt-circle-right"></span></button>
                            </div>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
</div>
<script>
    $('#comment_form').submit(function(e){
	e.preventDefault();
        if ($('#comment').val() == ""){
            alert_toast("Please input a message.", "danger");
            return false;
        }
        $.ajax({
            url:'../admin/function-file/ajax.php?action=save_comment',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    $('#comment').val("")
                    $.ajax({
                        url: '../admin/function-file/ajax.php?action=get_latest_comments',
                        method: 'GET',
                        success: function(comments) {
                            $('.chat-box').html(comments);
                        }
                    })
                }
            }
        })
    })
    $(document).ready(function(){
        $.ajax({
            url: '../admin/function-file/ajax.php?action=get_latest_comments',
            method: 'GET',
            success: function(comments) {
            $('.chat-box').html(comments);
            }
        });
    $(".chat-box").on("DOMSubtreeModified",function(){
    $(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
    });
});
</script>