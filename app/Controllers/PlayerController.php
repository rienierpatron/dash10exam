<?php

namespace App\Controllers;

use App\Http\Http;
use Tightenco\Collect\Support\Collection;

class PlayerController
{
    /**
     * Show a player profile
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($game_type = null)
    {
        $game_type = $game_type ?? 'rugby';

        $id = $id ?? 1;

        $player = $this->player($id, $game_type);

        //echo "<pre>"; print_r($player);exit;

        if ($game_type == 'rugby') {
            $player->put('image', 'static/images/players/allblacks/' . $this->image($player->get('name')));
        } else {
            $player->put('image', 'static/images/players/nba/' . $this->image($player->get('first_name') . ' ' . $player->get('last_name')));
        }

        $player->put('game_type', $game_type);
        //echo "<pre>"; print_r($player);exit;

        return view('player', $player);
    }

    /**
     * Retrieve player data from the API
     *
     * @param int $id
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function player(int $id, String $game_type): Collection
    {

        if ($game_type == 'rugby') {
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/allblacks';
        } else {
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nba.players'; 
        }

        $json = Http::get("$baseEndpoint/id/$id", [
            'API_KEY' => env('API_KEY'),
        ])->json();

        if ($game_type == 'nba') {

            $featuresEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nba.stats';

            $features = Http::get("$featuresEndpoint", [
                'API_KEY' => env('API_KEY'),
            ])->json();

            $stats_found = false;
            for ($index = 0; $index < sizeOf($features); $index++) {
                if ($features[$index]->player_id == $id) {
                    $json[0]->assists = round($features[$index]->assists/$features[$index]->games,2);
                    $json[0]->points = round($features[$index]->points/$features[$index]->games,2);
                    $json[0]->rebounds = round($features[$index]->rebounds/$features[$index]->games,2);

                    $stats_found = true;
                    break;
                }
            }

            if (!$stats_found) {
                $json[0]->assists = "N/A";
                    $json[0]->points = "N/A";
                    $json[0]->rebounds = "N/A";
            }
        }

        return collect(array_shift($json));
    }

    /**
     * Determine the image for the player based off their name
     *
     * @param string $name
     * @return string filename
     */
    protected function image(string $name): string
    {
        return preg_replace('/\W+/', '-', strtolower($name)) . '.png';
    }

    /**
     * Build stats to feature for this player
     *
     * @param \Illuminate\Support\Collection $player
     * @return \Illuminate\Support\Collection features
     */
    protected function feature(Collection $player, String $game_type): Collection
    {
        //echo "<pre>"; print_r($player);exit;
        if ($game_type == "rugby") {
            return collect([
                ['label' => 'Points', 'value' => $player->get('points')],
                ['label' => 'Games', 'value' => $player->get('games')],
                ['label' => 'Tries', 'value' => $player->get('tries')],
            ]);
        } else {
            return collect([
                ['label' => 'Assists Per Game', 'value' => $player->get('assists')],
                ['label' => 'Points Per Game', 'value' => $player->get('points')],
                ['label' => 'Rebounds Per Game', 'value' => $player->get('rebounds')],
            ]);
        }
    }

    public function list(int $id, String $game_type): Collection
    {
        $player = $this->player($id, $game_type);

        if ($game_type == 'rugby') {
            $names = collect(preg_split('/\s+/', $player->get('name')));
            $player->put('last_name', $names->pop());
            $player->put('first_name', $names->join(' '));
    
            $player->put('image', 'static/images/players/allblacks/' . $this->image($player->get('name')));
            $player->put('logo', 'static/images/teams/allblacks.png');
            $player->put('current_team', 'All Blacks');
            $player->put('header', 'All Blacks Rugby');
    
            $player->put('featured', $this->feature($player, $game_type));
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/allblacks';
        } else {
            $player->put('name', $player->get('first_name') . ' ' . $player->get('last_name'));
            $player->put('image', 'static/images/players/nba/' . $this->image($player->get('first_name') . ' ' . $player->get('last_name')));
            $player->put('logo', 'static/images/teams/'.strtolower($player->get('current_team')).'.png');
            $player->put('header', 'NBA Basketball');
            $player->put('height', $player->get('feet') . "'" . $player->get('feet') . "\"");
            $player->put('featured', $this->feature($player, $game_type));

            $age = date_diff(date_create($player->get('birthday')), date_create(date('Y-m-d')));
            $player->put('age', $age->format('%y'));
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nba.players';
        }


        $list = Http::get($baseEndpoint, [
            'API_KEY' => env('API_KEY'),
        ])->json();

        return collect(['list' => $list, 'selected' => $player]);
    }

    public function player_info(int $id, String $game_type): Collection
    {
        $player = $this->player($id, $game_type);

        if ($game_type == 'rugby') {
            $names = collect(preg_split('/\s+/', $player->get('name')));
            $player->put('last_name', $names->pop());
            $player->put('first_name', $names->join(' '));
    
            $player->put('image', 'static/images/players/allblacks/' . $this->image($player->get('name')));
            $player->put('logo', 'static/images/teams/allblacks.png');
            $player->put('current_team', 'All Blacks');
            $player->put('header', 'All Blacks Rugby');
    
            $player->put('featured', $this->feature($player));
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/allblacks';
        } else {
            $player->put('name', $player->get('first_name') . ' ' . $player->get('last_name'));
            $player->put('image', 'static/images/players/nba/' . $this->image($player->get('first_name') . ' ' . $player->get('last_name')));
            $player->put('logo', 'static/images/teams/'.strtolower($player->get('current_team')).'.png');
            $player->put('header', 'NBA Basketball');
            $player->put('height', $player->get('feet') . "'" . $player->get('feet') . "\"");
            $player->put('featured', $this->feature($player, $game_type));

            $age = date_diff(date_create($player->get('birthday')), date_create(date('Y-m-d')));
            $player->put('age', $age->format('%y'));
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nba.players';
        }

        return collect(['selected' => $player]);
    }
}
