<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

// upload du fichier sur le serveur

$client = new Client();
$response = $client->request('POST', 'https://realfavicongenerator.net/api/favicon', [
	'json' => [
		'favicon_generation' => [
	        'api_key' => 'fe4b050b6b381550394499533a1a1fd3cc83c4f8',
	        'master_picture' => [
	            'type' => 'inline',
	            'content' => base64_encode(file_get_contents($_FILES['favicon']['tmp_name'])),
	        ],
	        'files_location' => [
	            'type' => 'path',
	            'path' => '/Users/elisee/projects/sites/favicon/image',
	        ],
	        'favicon_design' => [
	        	'desktop_browser' => [],
	        	'ios' => [
	        		'picture_aspect' => 'background_and_margin',
					'margin' => '4',
					'background_color' => '#ffffff',
					'startup_image' => [
						'master_picture' => [
							'type' => 'inline',
							'content' => base64_encode(file_get_contents($_FILES['favicon']['tmp_name'])),
						],
						'background_color' => '#ffffff',
					],

					'assets' => [
						'ios6_and_prior_icons' => true,
						'ios7_and_later_icons' => true,
						'precomposed_icons' => false,
						'declare_only_default_icon' => true,
					],
	        	],

	        	'windows' => [
	        		'picture_aspect' => 'white_silhouette',
	        		'background_color' => '#ffffff',
	        		'assets' => [
	        			'windows_80_ie_10_tile' => true,
	        			'windows_10_ie_11_edge_tiles' => [
	        				'small' => false,
	        				'medium' => true,
	        				'big' => true,
	        				'rectangle' => false
	        			],
	        		],
	        	],

	        	'android_chrome' => [
	        		'picture_aspect' => 'shadow',
	        		'manifest' => [
	        			'name' => 'app windows',
	        			'display' => 'standalone',
	        			'orientation'=> 'portrait',
	        			'start_url' => '/homepage.html',
	        			'existing_manifest' => [ 'name' => 'yet another app'],
	        		],
	        		'assets' => [
	        			'legacy_icon' => true,
	        			'low_resolution_icons' => false,
	        		],
	        		'theme_color' => '#4972ab',
	        	],
	        	'safari_pinned_tab' => [
	        		'picture_aspect' => 'black_and_white',
	        		'threshold' => 60,
	        		'theme_color' => '#136497'
	        	],
	        ],
	        'settings' => [
	        	'compression' => true,
	        	'scaling_algorithm' => 'Mitchell',
	        	'error_on_image_too_small' => true,
	        	'readme_file' => true,
	        	'html_code_file' => true,
	        	'use_path_as_is' => true,

	        ],
	        'versioning' => [
	        	'param_name' => 'ver',
	        	'param_value' => '15Zd8'
	        ],
	        
	    ],
	]
]);

if ($response->getStatusCode() === 200) {
	$data = json_decode($response->getBody());
	if ($data->favicon_generation_result->result->status === 'success') {
		$url = $data->favicon_generation_result->favicon->package_url;

		$filename = '/Users/elisee/projects/sites/favicon/package.zip';
		if(copy($url, $filename)){
			$zip = new ZipArchive;
			try {
				$file = $zip->open($filename);
				if ($file === TRUE) {
					$zip->extractTo('/Users/elisee/projects/sites/favicon/image');
					$zip->close();
					unlink($filename); // delete package.zip 
					echo 'Extraction reussie';
				} else {
					echo 'erreur d\'extraction';
				}
			} catch (Exception $error) {
				var_dump($error->getMessage());
			}
		} else {
			echo 'erreur archive';
		}

	} else{
		echo 'ça ne marche pas (package_url non trouvé)';
	}
} else{
	echo 'ça ne marche pas (echec favicon result)';
}

