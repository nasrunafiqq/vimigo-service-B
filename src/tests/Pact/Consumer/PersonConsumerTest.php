<?php
declare(strict_types=1);
use GuzzleHttp\Client;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;

class PersonConsumerTest extends TestCase
{
    public function testGetPersonById()
    {
        $id = 1;
        $fav_genre = "rock";
        $interest = "listening";
        $playlist_history = "Romance";

        $request = new ConsumerRequest();
        $request->setMethod("GET")
            ->setPath("api/user/" . $id)
            ->addHeader("Accept", "application/json");

        $response = new ProviderResponse();
        $response->setStatus(200)
            ->addHeader("Content-Type", "application/json")
            ->setBody(
                [
                    "id" => $id,
                    "fav_genre" => $fav_genre,
                    "interest" => $interest,
                    "playlist_history" => $playlist_history,
                ]
            );

        $config = new MockServerEnvConfig();

        $builder = new InteractionBuilder($config);
        $builder->given("Playlist commendation is retrieve")
            ->uponReceiving("GET user for id: " . $id)
            ->with($request)
            ->willRespondWith($response);

        $client = new Client(["base_uri" => $config->getBaseUri()]);

        $this->assertTrue($builder->verify());
    }

}