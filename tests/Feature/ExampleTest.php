<?php

test('returns a redirect to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/admin/login');
});
