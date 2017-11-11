<?php
/**
 * @file functions.php
 * Functions used throughout the website.
 */
// Assure that config is imported prior to this file being executed.
require_once "config.php";

// Assure error reporting is on.
error_reporting(-1);
ini_set("display_errors", "On");

/**
 * Grabs a username based on the id.
 *
 * @param {int} $id: The unique id of the username.
 * @return {String}
 */
function findUsername($id) {
  global $mysqli;
  $statement = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
  $statement->bind_param("i", $id);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($username);
  $statement->fetch();

  return $username;
} // function findUsername($id);

/**
 * Determines if a user is logged in.
 *
 * @return {Boolean}
 */
function isLoggedIn() {
  return isset($_SESSION["logged-in"]) && $_SESSION["logged-in"];
} // function isLoggedIn()

/**
 * Determines if a user is a superuser or not.
 *
 * @return {Boolean}
 */
function isSuper() {
  return $_SESSION["user_role"] == "superuser";
} // function isSuper()

/**
 * Prints an example list.
 *
 * @param {Array} $array: The pre-determined array list of examples.
 */
function printExamples($array) {
  foreach ($array as $example) {
    print "<li>" . $example . "</li>";
  }
} // function printExamples($array)

/**
 * Prints multiple options in a select dropdown.
 *
 * @param {Array} $array: The pre-determined array list of options.
 */
function printOptions($array) {
  foreach ($array as $option) {
    print "<option>" . $option . "</option>";
  }
} // function printOptions($array)

/**
 * Renders MySQL values into HTML-ready entities.
 * - Currently only detects quotation marks.
 *
 * @param {String} $text: The MySQL value.
 * @param {Boolean} $ignore: Ignore the "value=''" attribute.
 */
function printValue($text, $ignore = false) {
  if ($ignore) {
    print preg_replace("/\"/", "&quot;", $text);
  } else {
    print 'value="' . preg_replace("/\"/", "&quot;", $text) . '"';
  }
} // function printValue($text, $ignore = false)

/**
 * Formats a date from machine to human readability.
 *
 * @param {String} $date: MySQL formatted date.
 * @return {String}
 */
function formatDate($date) {
  $date = new DateTime($date);
  return $date->format("F jS, Y - g:ia");
} // function formatDate($date)

/**
 * Unescapes HTML entities
 * - Currently only detects quotation marks.
 * - Unescapes double quotes but leaves other HTML entities intact
 *   if you want to remove all escaped HTML entities use htmlspecialchars_decode()
 * @param {String} $text: HTML text to be unescaped
 * @return {String}
 */
function unescapeHTMLEntities($text) {
  return preg_replace("/&quot;/", "\"", $text);
} // function unescapeHTMLEntities($text)

/**
 * Submit a comment on the governance page to the database.
 *
 * @param {String} $comment: The comment.
 * @return {Boolean}
 */
function submitGovernanceComment($comment) {
  global $mysqli;
  $userID  = $_SESSION["user_id"];
  $comment = htmlspecialchars(trim($comment));

  $statement = $mysqli->prepare("INSERT INTO constitution_comments (comment_text, date_submitted, user_id) VALUES (?, NOW(), ?)");
  $statement->bind_param("ss", $comment, $userID);
  $statement->execute();
  $statement->store_result();

  return $statement->affected_rows !== 0;
} // function submitGovernanceComments($comment)

/**
 * Renders all comments submitted on the governance page.
 */
function renderGovernanceComments() {
  global $mysqli;
  $statement = $mysqli->prepare("SELECT comment_text, date_submitted, user_id FROM constitution_comments ORDER BY date_submitted");
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($comment, $date, $userID);
  ?>
  <?php while ($statement->fetch()): ?>
    <div class="col-xs-8">
      <h4><?php print findUsername($userID); ?><time class="pull-right"><?php print formatDate($date); ?></time></h4>
      <p class="comment-text"><?php print $comment; ?></p>
      <hr>
    </div>
  <?php endwhile;
} // function renderGovernanceComments()

/**
 * Renders a hierarchy list of comments on the comments page.
 *
 * @param {String} $value: The value of the selected item.
 */
function renderComments($value) {
  global $mysqli;
  $original = $mysqli->prepare("SELECT id, $value, user_id FROM $value WHERE $value != ''");
  $original->execute();
  $original->store_result();
  $original->bind_result($commentID, $comment, $userID);
  ?>
  <?php while ($original->fetch()): ?>
    <?php
    $statement = $mysqli->prepare("SELECT id, reply_comment, replied_by FROM reply_$value WHERE comments_id = ?");
    $statement->bind_param("i", $commentID);
    $statement->execute();
    $statement->store_result();
    $statement->bind_result($replyID, $reply, $replier);
    ?>
    <div class="comment col-xs-9">
      <?php renderComment(findUsername($userID), $comment, $commentID, $value, "original"); ?>
    </div>
    <?php while ($statement->fetch()): ?>
      <div class="comment-reply col-xs-9">
        <?php renderComment($replier, $reply, $replyID, $value, "reply"); ?>
      </div>
    <?php endwhile; ?>
  <?php endwhile;
} // function renderComment($value)

/**
 * Renders the interior of the comment section.
 *
 * @param {String} $user: The user who posted the comment.
 * @param {String} $text: The comment text.
 * @param {int} $id: The comment ID.
 * @param {String} $table: The table corresponding to the comment. Used for AJAX.
 * @param {String} $type: Either "reply" or "original".
 */
function renderComment($user, $text, $id, $table, $type) {
  ?>
  <h4 class="pull-left" data-id="<?php print $id; ?>" data-tablename="<?php print $table; ?>"><?php print $user; ?></h4>
  <ul class="list-inline comment-tools">
    <?php if ($type == "reply" && isSuper()): ?>
      <li class="delete">Delete</li>
    <?php elseif ($type == "original"): ?>
      <li class="reply">Reply</li>
      <li class="breakdown">Collapse</li>
    <?php endif; ?>
  </ul>

  <p style="clear: both;"><?php print $text; ?></p>
  <?php
} // function renderComment($user, $text, $id, $table, $type)

/**
 * Renders a data table on the comments page.
 *
 * @param {String} $value: The value of the selected item.
 */
function renderTable($value) {
  global $mysqli;
  $statement;

  if ($value == "genre") {
    $statement = $mysqli->prepare("SELECT genre_required_available, genre_controlled_available, suggested_terms_genre, user_id FROM comments");
  } else if ($value == "type_available") {
    $statement = $mysqli->prepare("SELECT type_available, suggested_terms_type, user_id FROM comments");
  } else if ($value == "role_available") {
    $statement = $mysqli->prepare("SELECT role_available, suggested_terms_role, user_id FROM comments");
  } else {
    $statement = $mysqli->prepare("SELECT $value, user_id FROM comments");
  }

  $statement->execute();
  $statement->store_result();

  if ($value == "genre") {
    $statement->bind_result($required, $controlled, $suggested, $userID);
  } else if ($value == "type_available" || $value == "role_available") {
    $statement->bind_result($available, $suggested, $userID);
  } else {
    $statement->bind_result($commentColumn, $userID);
  }
  ?>
  <table class="table table-striped table-hover dt">
    <thead>
      <tr>
        <th>Username</th>

        <?php if ($value == "genre"): ?>
          <th>Required/Optional</th>
          <th>Controlled/Free-Form</th>
          <th>Suggested Terms</th>
        <?php elseif ($value == "type_available" || $value == "role_available"): ?>
          <th>Available</th>
          <th>Suggested Terms</th>
        <?php else: ?>
          <th>Decision</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php while ($statement->fetch()): ?>
        <tr>
          <td><?php print findUsername($userID); ?></td>

          <?php if ($value == "genre"): ?>
            <td><?php print $required == "true" ? "Required" : $required == "false" ? "Optional" : "<em>No data given</em>"; ?></td>
            <td><?php print $controlled == "true" ? "Controlled" : $required == "false" ? "Free-form" : "<em>No data given</em>"; ?></td>
            <td><?php print renderTableCell($suggested); ?></td>
          <?php elseif ($value == "type_available" || $value == "role_available"): ?>
            <td><?php print renderTableCell($available); ?></td>
            <td><?php print renderTableCell($suggested); ?></td>
          <?php else: ?>
            <td><?php print renderTableCell($commentColumn); ?></td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php
} // function renderTable($value)

/**
 * Renders a table cell's data.
 *
 * @param {String} $data: The data.
 * @return {String}
 */
function renderTableCell($data) {
  return $data === "" || $data === NULL ? "<em>No data given</em>" : $data;
} // function renderTabelCell($data)
