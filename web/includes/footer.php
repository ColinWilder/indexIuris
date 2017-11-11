<?php
/**
 * @file footer.php
 * Prints the closing of the HTML structure.
 */
?>
<footer class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal">
      <div class="modal-content">
        <div class="modal-header">
          <div class="alert" style="display: none;"></div>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
          <h4 class="modal-title">Contact</h4>
        </div>
        <div class="modal-body">
          <fieldset>
            <section class="form-group">
              <label for="name" class="control-label col-xs-2">Name</label>
              <div class="col-xs-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="First and Last Name">
              </div>
            </section>

            <section class="form-group">
              <label for="email" class="control-label col-xs-2">Email</label>
              <div class="col-xs-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="me@example.com">
              </div>
            </section>

            <section class="form-group">
              <label for="message" class="control-label col-xs-2">Message</label>
              <div class="col-xs-10">
                <textarea class="form-control" id="message" name="message"></textarea>
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-10 pull-right">
                <div class="g-recaptcha" data-sitekey="6LcrxgkTAAAAAKZk3YRaQzfxOB4qlJ1fyCRxXk8q"></div>
              </div>
            </section>
          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
    </form>
  </div>
</footer>

<?php foreach (array("jquery.min.js", "bootstrap.min.js", "dataTables.min.js", "dataTables.bootstrap.min.js", "platform.min.js", "collex.js") as $script): ?>
  <script src="js/<?php print $script; ?>"></script>
<?php endforeach; ?>

</body>
</html>
