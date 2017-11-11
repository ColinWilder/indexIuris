<?php
/**
 * @file logout.php
 * Logs a user out.
 */
session_start();

if (session_destroy()) {
  header("Location: ./");
}
