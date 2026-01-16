<?php

function prehash($secret) {
    return hash("sha256", $secret);
}

function make_hash($secret) {
    return password_hash(prehash($secret), PASSWORD_BCRYPT);
}

function verify_hash($secret, $hash) {
    return password_verify(prehash($secret), $hash);
}

function canon_level1($images) {
    sort($images);
    return "L1:" . implode(",", $images);
}

function canon_level3($sequence) {
    return "L3:" . implode(",", $sequence);
}

function quantize_points($points, $grid = 20) {
    $cells = [];
    foreach ($points as $p) {
        $cx = floor($p[0] / $grid);
        $cy = floor($p[1] / $grid);
        $cells[] = "$cx:$cy";
    }
    return "L2:" . implode(";", $cells);
}
