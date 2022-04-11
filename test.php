<?php

$teams = [1, 2, 3, 4];

class Node
{
    public int $team;
    public Node $next;
}

$nodes = [];
foreach ($teams as $team) {
    $node = new Node();
    $node->team = $team;

    $nodes[] = $node;
}

foreach ($nodes as $index => $node) {
    if ($index === (count($nodes) - 1)) {
        $node->next = $nodes[0];
    } else {
        $node->next = $nodes[$index + 1];
    }
}

for ($i = 0; $i < count($teams) - 1; $i++) {
    foreach ($nodes as $nodeIndex => $node) {
        $node->next = $node->next->next;
    }
}

//$games[] = $teams;
//for ($i = 0; $i < (count($teams) - 2) ; $i++) {
//    $game[] = $teams[0];
//    foreach ($games[count($games) - 1] as $index => $team) {
//        if ($index === count($teams) - 1) {
//            $game[1] = $team;
//        } else {
//            $game[$index + 1] = $team;
//        }
//    }
//
//    $games[] = $game;
//}
//
//print_r($games);
