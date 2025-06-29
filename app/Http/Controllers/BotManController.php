<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class BotManController extends Controller
{
    public function handle(Request $request)
    {
        // Load the Web Driver
        DriverManager::loadDriver(WebDriver::class);

        // Create BotMan instance
        $botman = BotManFactory::create([]);

        // Reusable function to show question buttons
        $suggestedQuestions = function(BotMan $bot, $message = "How can I assist you?") {
            $question = Question::create($message)
                ->addButtons([
                    Button::create('What are your hours?')->value('what are your hours'),
                    Button::create('How to contact support?')->value('contact support'),
                    Button::create("What's on the menu?")->value('show menu'),
                ]);

            $bot->reply($question);
        };

        // Initial greeting on chat open
        $botman->hears('start_conversation', function (BotMan $bot) use ($suggestedQuestions) {
            $suggestedQuestions($bot, "👋 Hi there! How can I assist you today?");
        });

        // Greeting trigger by user input
        $botman->hears('hi|hello', function (BotMan $bot) use ($suggestedQuestions) {
            $suggestedQuestions($bot, "👋 Hello! What would you like to ask?");
        });

        // Hours response
        $botman->hears('what are your hours', function (BotMan $bot) use ($suggestedQuestions) {
            $bot->reply("🕘 We are open daily from 9 AM to 8 PM.");
            $suggestedQuestions($bot, "ℹ️ Anything else you want to know?");
        });

        // Support response
        $botman->hears('contact support', function (BotMan $bot) use ($suggestedQuestions) {
            $bot->reply("📞 You can reach us at support@example.com or call 123-456-7890.");
            $suggestedQuestions($bot, "📌 Do you need more help?");
        });

        // Menu response
        $botman->hears('show menu', function (BotMan $bot) use ($suggestedQuestions) {
            $bot->reply("🍽️ We offer burgers, pasta, and drinks! Visit our menu page to see more.");
            $suggestedQuestions($bot, "🍴 Want to ask something else?");
        });

        // Fallback for unrecognized input
        $botman->fallback(function (BotMan $bot) use ($suggestedQuestions) {
            $bot->reply("❓ Sorry, I didn’t understand that.");
            $suggestedQuestions($bot, "⬇️ Please choose one of the options below:");
        });

        // Start listening
        $botman->listen();
    }
}
