<?php
/**
 * @file submissions.php
 * Prints the User's Submissions page.
 */
$title = "View Submissions";
$loginRequired = true;
require "includes/header.php";
?>

<div class="container">
  <div class="row page-header">
    <div class="row">
      <div class="col-xs-12">
        <h1>View Submissions</h1>
        <p><?php print isSuper() ? "Superuser View" : "Contributor View"; ?></p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table table-striped table-hover dt">
        <thead>
          <tr>
            <th>Title</th>
            <th>Archive</th>
            <th>Type</th>
            <?php if (isSuper()): ?>
              <th>User</th>
            <?php endif; ?>
            <th>Tools</th>
          </tr>
        </thead>
        <tbody>
          <?php
          global $mysqli;
          $statement;

          if (isSuper()) {
            $statement = $mysqli->prepare("SELECT id, title, archive, type, user_id FROM objects");
          } else {
            $statement = $mysqli->prepare("SELECT id, title, archive, type FROM objects WHERE user_id = ?");
            $statement->bind_param("i", $_SESION["user_id"]);
          }

          $statement->execute();
          $statement->store_result();

          if (isSuper()) {
            $statement->bind_result($id, $title, $archive, $type, $userID);
          } else {
            $statement->bind_result($id, $title, $archive, $type);
          }
          ?>
          <?php while ($statement->fetch()): ?>
            <tr>
              <td><?php print $title; ?></td>
              <td><?php print $archive; ?></td>
              <td><?php print $type; ?></td>
              <?php if (isSuper()): ?>
                <td><?php print findUsername($userID); ?></td>
              <?php endif; ?>
              <td>
                <a href="view?id=<?php print $id; ?>" class="btn btn-sm btn-primary">View</a>
                <a href="edit?id=<?php print $id; ?>" class="btn btn-sm btn-success">Edit</a>
                <a href="rdf?id=<?php print $id; ?>" class="btn btn-sm btn-default">RDF</a>
                <span class="hide"><?php print $id; ?></span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>
