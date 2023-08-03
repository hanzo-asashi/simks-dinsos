<?php


namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvFileService
{

    /**
     * @return Collection
     */
    public function getAllEnv() : Collection
    {
        $envDetails = DotenvEditor::getKeys();
        $envDetails = new Collection($envDetails);
        return $envDetails->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->groupBy(function ($item, $key) {
            $key = explode('_', $key);
            return $key[0];
        });
    }

    /**
     * Get the specified env data.
     * @param  array  $env
     * @return Collection
     */
    public function getEnv(array $env) : Collection
    {
        $envDetails = DotenvEditor::getKeys($env);
        $envDetails = new Collection($envDetails);
        return $envDetails->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->groupBy(function ($item, $key) {
            $key = explode('_', $key);
            return $key[0];
        });
    }

    /**
     * @param  Request  $request
     * @return Collection
     */
    public function updateEnv(Request $request) : Collection
    {
        $keys = DotenvEditor::getKeys($request->keys());

        array_walk($keys, function ($data, $key) use ($request) {
            if ($request->input($key) != $data['value']) {
                DotenvEditor::setKey($key, $request->input($key));
            }
        });

        DotenvEditor::save();

        return $this->getEnv($request->keys());
    }


}
