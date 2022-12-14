<?php

function get_user_projects(mysqli $mysqli, int $user_id): array
{
    $user_id = intval($user_id);
    $sql = "SELECT * FROM `project` WHERE `user_id` = {$user_id}";
    $result = mysqli_query($mysqli, $sql);

    $projects = [];

    while ($project = mysqli_fetch_assoc($result)) {
        $projects[] = $project;
        // array_push($projects, `project`)
    }

    return $projects;
}

function get_user_tasks(mysqli $mysqli, int $user_id): array
{
    $sql = "SELECT * FROM `task` WHERE `user_id` = {$user_id}";
    $result = mysqli_query($mysqli, $sql);

    $tasks = [];

    while ($task = mysqli_fetch_assoc($result)) {
        $tasks[] = $task;
        // array_push($projects, `project`)
    }

    return $tasks;
}

function create_user(mysqli $mysqli, array $data): bool
{
    list($username, $email, $password) = $data;
    // [$username, $email, $password] = $data;

    $username = mysqli_real_escape_string($mysqli, $username);
    $email = mysqli_real_escape_string($mysqli, $email);
    $password = mysqli_real_escape_string($mysqli, $password);
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    $data = implode(', ', ["'$username'", "'$email'", "'$password'"]); //VALUES ("$username", "$email", "$password")
    // $data = "'{$username}', '{$email}', '${password}'";

    $sql = "INSERT INTO user (login, email, password_hash) VALUES ($data)";

    return mysqli_query($mysqli, $sql);
}

function get_user_by_email(mysqli $mysqli, string $email): ?array
{
    $email = mysqli_real_escape_string($mysqli, $email);
    $sql = "SELECT * FROM `user` WHERE `email` = '{$email}'";

    if (!$result = mysqli_query($mysqli, $sql)) {
        return null;
    }

    return mysqli_fetch_assoc($result);
}

function is_email_exist(mysqli $mysqli, string $email): bool
{
    $email = mysqli_real_escape_string($mysqli, $email);
    $sql = "SELECT * FROM `user` WHERE `email` = '{$email}'";
    $result = mysqli_query($mysqli, $sql);

    return boolval(mysqli_fetch_assoc($result));
}

function is_project_exist(mysqli $mysqli, int $user_id, string $project): bool
{
    $project = mysqli_real_escape_string($mysqli, $project);
    $sql = "SELECT * FROM `project` WHERE `user_id` = $user_id AND `name` = '$project'";
    $result = mysqli_query($mysqli, $sql);

    return boolval(mysqli_fetch_assoc($result));
}

function create_project(mysqli $mysqli, int $user_id, string $project): bool
{
    $project = mysqli_real_escape_string($mysqli, $project);
    $sql = "INSERT INTO project (name, user_id) VALUES ('$project', $user_id)";

    return mysqli_query($mysqli, $sql);
}
