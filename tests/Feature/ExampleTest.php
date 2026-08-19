<?php

/** Goal: Verify home page redirects, Caller: Pest, Deps: None */
it('returns a redirect to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
