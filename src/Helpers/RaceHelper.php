<?php

namespace OpenDominion\Helpers;

use LogicException;
use OpenDominion\Models\Race;

class RaceHelper
{
    public function getRaceDescriptionHtml(Race $race): string
    {
        $descriptions = [];

        // Good races

        $descriptions['dwarf'] = <<<DWARF
<p>Defined by their majestic beards and their love for booze and labor, these descendants of Caedair Hold have come to fight for the forces of good.</p>
<p>Short and grumpy, they harbor an intense hatred towards Goblins.</p>
<p class="text-green">
    Increased max population<br>
    Increased ore production
</p>
DWARF;

        $descriptions['human'] = <<<HUMAN
<p>These noble and religious Humans hail from fallen city of Brimstone Keep.</p>
<p>Proficient at everything but excelling at nothing, they are a well-balanced and self-sufficient race.</p>
<p class="text-green">
    Increased food production
</p>
HUMAN;

        $descriptions['firewalker'] = <<<FIREWALKER
<p>Beings of pure fire, which came into this world after a powerful sorcerer once got too greedy with their pyro experimentation projects.</p>
<p>Excellent at proliferation, these fiery beasts seem highly interested in leaving only ash in their wake.</p>
<p class="text-green">
    Increased max population<br>
    Increased gem production<br>
    Reduced construction costs<br>
    <span class="text-red">
        Reduced lumber production    
    </span>
</p>
FIREWALKER;
        // todo: ^ span in p? hacky hacky, move to yml pls

        // Evil races

        $descriptions['goblin'] = <<<GOBLIN
<p>What they lack in intelligence, they make up for in sheer numbers. They love slaughtering other living things as much as they love shiny gems.</p>
<p>Short, cunning, and gnarling, they hate anything that smells like Dwarf.</p>
<p class="text-green">
    Increased max population<br>
    Increased gem production<br>
    Improved castle bonuses
</p>
GOBLIN;

        $descriptions['nomad'] = <<<NOMAD
<p>Descendants of Humans, these folk have been exiled from the kingdom long ago and went their own way.</p>
<p>Acclimated to the desert life, these traveling Nomads teamed up with the evil races out of spite towards the Humans and their allies.</p>
<p class="text-green">
    Increased mana production
</p>
NOMAD;

        $descriptions['lizardfolk'] = <<<LIZARDFOLK
<p>These amphibious creatures hail from the depths of the seas, having remained mostly hidden for decades before resurfacing and joining the war.</p>
<p>Lizardfolk are highly proficient at both performing and countering espionage operations, and make for excellent incursions on unsuspecting targets.</p>
<p class="text-green">
    Increased max population<br>
    Increased spy strength<br>
    Reduced food consumption<br>
    No boats needed
</p>
LIZARDFOLK;

        $key = strtolower($race->name);

        if (!isset($descriptions[$key])) {
            throw new LogicException("Racial description for {$key} needs implementing");
        }

        return $descriptions[$key];
    }
}
