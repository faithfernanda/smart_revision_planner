<?php
// public/index.php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/Router.php';

$router = new Router();

// Define Routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/maintenance', 'HomeController@maintenance');
$router->add('GET', '/login', 'AuthController@loginForm');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@registerForm');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/verify-email', 'AuthController@verifyEmailForm');
$router->add('POST', '/verify-email', 'AuthController@verifyEmail');
$router->add('GET', '/resend-verification', 'AuthController@resendVerificationCode');
$router->add('GET', '/forgot-password', 'AuthController@forgotPasswordForm');
$router->add('POST', '/forgot-password', 'AuthController@forgotPassword');
$router->add('POST', '/verify-reset-code', 'AuthController@verifyResetCode');
$router->add('POST', '/reset-password', 'AuthController@resetPassword');

$router->add('GET', '/dashboard', 'DashboardController@index');
$router->add('POST', '/dashboard/generate', 'DashboardController@generate');

// Subjects
$router->add('GET', '/subjects', 'SubjectController@index');
$router->add('GET', '/subjects/create', 'SubjectController@create');
$router->add('POST', '/subjects/create', 'SubjectController@create');
$router->add('GET', '/subjects/edit', 'SubjectController@edit');
$router->add('POST', '/subjects/update', 'SubjectController@update');
$router->add('POST', '/subjects/delete', 'SubjectController@delete');

// Exams
$router->add('GET', '/exams', 'ExamController@index');
$router->add('GET', '/exams/create', 'ExamController@create');
$router->add('POST', '/exams/create', 'ExamController@create');
$router->add('GET', '/exams/edit', 'ExamController@edit');
$router->add('POST', '/exams/update', 'ExamController@update');
$router->add('POST', '/exams/delete', 'ExamController@delete');

// Availability
$router->add('GET', '/availability', 'AvailabilityController@index');
$router->add('GET', '/availability/create', 'AvailabilityController@create');
$router->add('POST', '/availability/create', 'AvailabilityController@create');
$router->add('POST', '/availability/delete', 'AvailabilityController@delete');

// Pomodoro
$router->add('GET', '/pomodoro', 'PomodoroController@index');
$router->add('POST', '/pomodoro/save', 'PomodoroController@save');

// Schedule
$router->add('GET', '/schedule', 'ScheduleController@index');
$router->add('GET', '/schedule/events', 'ScheduleController@events');
$router->add('POST', '/schedule/complete', 'ScheduleController@complete');
$router->add('POST', '/schedule/update', 'ScheduleController@update');
$router->add('POST', '/schedule/delete', 'ScheduleController@delete');
$router->add('GET', '/schedule/print', 'ScheduleController@print');
$router->add('GET', '/schedule/check-upcoming', 'ScheduleController@checkUpcoming');

// Analytics
$router->add('GET', '/analytics', 'AnalyticsController@index');

// Profile
$router->add('GET', '/profile', 'ProfileController@index');
$router->add('POST', '/profile/update', 'ProfileController@update');
$router->add('POST', '/profile/password', 'ProfileController@updatePassword');

// Notifications
$router->add('GET', '/notifications/count', 'NotificationController@count');
$router->add('GET', '/notifications/list', 'NotificationController@list');
$router->add('POST', '/notifications/read-all', 'NotificationController@readAll');

// Administration
$router->add('GET', '/admin', 'AdminController@index');
$router->add('GET', '/admin/users', 'AdminController@users');
$router->add('POST', '/admin/users/delete', 'AdminController@deleteUser');
$router->add('GET', '/admin/settings', 'SettingsController@index');
$router->add('POST', '/admin/settings/update', 'SettingsController@update');

$router->add('GET', '/lang', 'LanguageController@switch');
$router->add('POST', '/logout', 'AuthController@logout');

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
