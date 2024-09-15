<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseEvent;
use App\Models\CourseGroup;
use App\Models\Event;
use App\Models\Group;
use App\Models\Slot;
use DateTime;
use Illuminate\Database\Seeder;

class EventsErstiwocheSeeder extends Seeder
{
    /**
     * Set path to the file with telegram data.
     *
     * @var string
     */
    private const TELEGRAM_CSV_PATH = __DIR__ . '/telegram.csv';

    /**
     * Run the events seeds.
     */
    public function run(): void
    {
        $telegram_links = $this->parseTelegramCsv();
        $this->runGruppenphase();
        $this->runGruppenphaseISMaster();
        $this->runStadtrallye($telegram_links);
        $this->runHausfuehrung();
        $this->runKneipentour($telegram_links);
        $this->runKaterbrunch();
        $this->runKultur();
        $this->runSport();
    }

    /**
     * Parse the 'telegram.csv' file.
     */
    private function parseTelegramCsv(): array
    {
        // check if the telegram file exists
        if (! file_exists(self::TELEGRAM_CSV_PATH)) {
            return [];
        }

        // read the telegram.csv file
        $data = array_map('str_getcsv', file(self::TELEGRAM_CSV_PATH));

        // remove the header row
        array_shift($data);

        // save telegram links
        $telegram_links = [];

        // loop through the data
        foreach ($data as $row) {
            $telegram_link = explode(';', $row[0]);

            $event_name = $telegram_link[0];
            $group_or_slot_name = $telegram_link[1];
            $link = $telegram_link[2];

            $telegram_links[$event_name][$group_or_slot_name] = $link;
        }

        return $telegram_links;
    }

    /**
     * Run the "Gruppenphase" event seeds.
     */
    public function runGruppenphase(): void
    {
        // check if event with name "Gruppenphase" exists
        $event = Event::where('name', 'Gruppenphase')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Gruppenphase';
        $event->description = '<p>Während der Gruppenphase erhältst du von deinen Tutoren und Tutorinnen wichtige Informationen rund um das Studium. Außerdem ist die Gruppenphase dazu da, um direkt die anderen Erstis kennenzulernen und erste Freundschaften zu schließen.</p>';
        $event->type = 'group_phase';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-23 12:30:00');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 100;

        // save the event
        $event->save();

        // create event groups
        $groupNames = [
            'Die waghalsigen Waschbären',
            'Die kuscheligen Koalas',
            'Die originellen Opossums',
            'Die peppigen Pinguine',
            'Die risikofreudigen Rentiere',
            'Die fluffigen Flamingos',
            'Die kreisförmigen Karpfen',
            'Die dramatischen Dackel',
            'Die oszillierten Ozelots',
            'Die zappelnden Zitterale',
            'Die schnellen Schildkröten',
            'Die schicken Spinnen',
            'Die netten Nasenbären',
            'Die putzigen Pandas',
            'Die tapferen Tucans',
            'Die klugen Krokodile',
            'Die wundervollen Wallabys',
            'Die kantigen Kaninchen',
            'Die allwissenden Aale',
            'Die erfahrenen Enten',
        ];
        foreach ($groupNames as $groupName) {
            $group = new Group;
            $group->name = $groupName;
            $group->event_id = $event->id;
            $group->save();
        }

        // get all courses
        $courses = Course::all();

        // map courses by abbreviation
        $coursesByAbbreviation = [];
        foreach ($courses as $course) {
            $coursesByAbbreviation[$course->abbreviation] = $course;
        }

        // allowed courses
        $allowedCourses = [
            'INF',
            'ET',
            'DIB',
            'MCD',
            'WI',
            'SBE',
            'ISE-Master',
            'ET-Master',
            'INF-Master',
            // only 'IS-Master' is excluded here
        ];

        // save courses
        foreach ($allowedCourses as $courseAbbreviation) {
            $course = $coursesByAbbreviation[$courseAbbreviation];
            $course_event = new CourseEvent;
            $course_event->course_id = $course->id;
            $course_event->event_id = $event->id;
            $course_event->save();
        }
    }

    /**
     * Run the "Gruppenphase" event seeds.
     */
    public function runGruppenphaseISMaster(): void
    {
        // check if event with name "Gruppenphase" exists
        $event = Event::where('name', 'Gruppenphase')->where('description', 'like', '%' . '<strong>M.Sc. Information Systems</strong>' . '%')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Gruppenphase';
        $event->description = '<p>Während der Gruppenphase erhältst du von deinen Tutoren und Tutorinnen wichtige Informationen rund um das Studium. Außerdem ist die Gruppenphase dazu da, um direkt die anderen Erstis kennenzulernen und erste Freundschaften zu schließen.</p>
        <p>Diese Gruppenphase ist speziell für Studierende des Studiengangs <strong>M.Sc. Information Systems</strong>, da dort einige Besonderheiten erklärt werden.</p>';
        $event->type = 'group_phase';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-23 12:30:00');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 101;

        // save the event
        $event->save();

        // create event groups
        $groupNames = [
            'Die masterhaften Mammuts',
        ];
        foreach ($groupNames as $groupName) {
            $group = new Group;
            $group->name = $groupName;
            $group->event_id = $event->id;
            $group->save();
        }

        // get all courses
        $courses = Course::all();

        // map courses by abbreviation
        $coursesByAbbreviation = [];
        foreach ($courses as $course) {
            $coursesByAbbreviation[$course->abbreviation] = $course;
        }

        // allowed courses
        $allowedCourses = [
            'IS-Master',
        ];

        // save courses
        foreach ($allowedCourses as $courseAbbreviation) {
            $course = $coursesByAbbreviation[$courseAbbreviation];
            $course_event = new CourseEvent;
            $course_event->course_id = $course->id;
            $course_event->event_id = $event->id;
            $course_event->save();
        }
    }

    /**
     * Run the "Stadtrallye" event seeds.
     */
    public function runStadtrallye(array $telegram_links): void
    {
        // check if event with name "Stadtrallye" exists
        $event = Event::where('name', 'Stadtrallye')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Stadtrallye';
        $event->description = '<p>Die Stadtrallye ist ein Event, bei dem du in Gruppen die Stadt erkundest. Dabei gibt es verschiedene Aufgaben, die ihr lösen müsst. Dabei könnt ihr euch gegenseitig unterstützen und euch so besser kennenlernen.</p><p><strong>Treffpunkt: </strong> 9:00 Uhr Campus Eupener Straße</p>';
        $event->type = 'group_phase';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-24 09:45:00');
        $event->has_requirements = false;
        $event->consider_alcohol = true;
        $event->sort_order = 110;

        // save the event
        $event->save();

        // create event groups
        $groups = [];

        for ($i = 1; $i <= 14; $i++) {
            $groups[] = [
                'name' => "Gruppe $i",
                'telegram_group_link' => $telegram_links[$event->name]["Gruppe $i"] ?? null,
            ];
        }

        // save groups
        foreach ($groups as $groupData) {
            $group = new Group;
            $group->name = $groupData['name'];
            $group->event_id = $event->id;
            $group->telegram_group_link = array_key_exists('telegram_group_link', $groupData) ? $groupData['telegram_group_link'] : null;
            $group->save();
        }
    }

    /**
     * Run the "Hausführung" event seeds.
     */
    public function runHausfuehrung(): void
    {
        // check if event with name "Hausführung" exists
        $event = Event::where('name', 'Hausführung')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Hausführung';
        $event->description = '<p>Nachdem ihr nun die Stadt erkundet habt, ist es Zeit auch mal eure Hochschule von innen zu sehen. In der Hausführung erwarten euch sowohl Informationen über wichtige Stationen am Campus, die ihr während eurer Studienzeit sicherlich das ein oder andere Mal aufsuchen werdet, als auch die Möglichkeit, einige eurer Professoren und ein paar ihrer Projekte kennenzulernen. Durch die Aufteilung nach Studiengang ist es auch eine gute Möglichkeit, schonmal Bekanntschaft mit euren Sitznachbarn in den Vorlesungen zu machen.</p>';
        $event->type = 'group_phase';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-25 9:30:00');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 120;

        // save the event
        $event->save();

        // get all courses
        $courses = Course::all();

        // map courses by abbreviation
        $coursesByAbbreviation = [];
        foreach ($courses as $course) {
            $coursesByAbbreviation[$course->abbreviation] = $course;
        }

        // create event groups
        $groups = [];

        for ($i = 1; $i <= 10; $i++) {
            $groups[] = [
                'name' => "INF Hausführung $i",
                'course_ids' => [
                    $coursesByAbbreviation['INF']->id,
                    $coursesByAbbreviation['INF-Master']->id,
                    $coursesByAbbreviation['ISE-Master']->id,
                    $coursesByAbbreviation['SBE']->id
                ],
            ];
        }
        for ($i = 1; $i <= 5; $i++) {
            $groups[] = [
                'name' => "ET Hausführung $i",
                'course_ids' => [
                    $coursesByAbbreviation['ET']->id,
                    $coursesByAbbreviation['ET-Master']->id
                ],
            ];
        }
        for ($i = 1; $i <= 3; $i++) {
            $groups[] = [
                'name' => "DIB Hausführung $i",
                'course_ids' => [
                    $coursesByAbbreviation['DIB']->id,
                    $coursesByAbbreviation['MCD']->id
                ],
            ];
        }
        for ($i = 1; $i <= 3; $i++) {
            $groups[] = [
                'name' => "WI Hausführung $i",
                'course_ids' => [
                    $coursesByAbbreviation['WI']->id,
                ],
            ];
        }

        for ($i = 1; $i <= 1; $i++) {
            $groups[] = [
                'name' => "IS-Master Hausführung $i",
                'course_ids' => [
                    $coursesByAbbreviation['IS-Master']->id
                ],
            ];
        }

        // save groups
        foreach ($groups as $groupData) {
            $group = new Group;
            $group->name = $groupData['name'];
            $group->event_id = $event->id;
            $group->save();

            // save course_group collections
            foreach ($groupData['course_ids'] as $course_id) {
                $course_group = new CourseGroup;
                $course_group->course_id = $course_id;
                $course_group->group_id = $group->id;
                $course_group->save();
            }
        }
    }

    /**
     * Run the "Kneipentour" event seeds.
     */
    public function runKneipentour(array $telegram_links): void
    {
        // check if event with name "Kneipentour" exists
        $event = Event::where('name', 'Kneipentour')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Kneipentour';
        $event->description = '<p>Sei Teil unserer Kneipentour, um die besten Bars zu entdecken, unterhaltsame Spiele zu genießen und deine Kommilitonen kennenzulernen.</p>';
        $event->type = 'group_phase';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-25 17:00:00');
        $event->has_requirements = false;
        $event->consider_alcohol = true;
        $event->sort_order = 130;

        // save the event
        $event->save();

        // create event groups
        $groups = [];

        for ($i = 1; $i <= 24; $i++) {
            $groups[] = [
                'name' => "Gruppe $i",
                'telegram_group_link' => $telegram_links[$event->name]["Gruppe $i"] ?? null,
            ];
        }

        // save groups
        foreach ($groups as $groupData) {
            $group = new Group;
            $group->name = $groupData['name'];
            $group->event_id = $event->id;
            $group->telegram_group_link = array_key_exists('telegram_group_link', $groupData) ? $groupData['telegram_group_link'] : null;
            $group->save();
        }
    }

    /**
     * Run the "Katerbrunch" event seeds.
     */
    public function runKaterbrunch(): void
    {
        // check if event with name "Katerbrunch" exists
        $event = Event::where('name', 'Katerbrunch')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Katerbrunch';
        $event->description = '<p>Nachdem wir alle nach der Kneipentour am Mittwoch Abend etwas verkatert sind, gibt es doch nichts besseres als zusammen bei einem guten Fr&uuml;hst&uuml;ck auszukatern 😊 <br />Hierf&uuml;r bitte wir euch die 2&euro; Anmeldegeb&uuml;hr am Montag zwischen 13:45 und 16:00 Uhr oder Mittwoch zwischen 12:00 und 14:30 Uhr im FSR zu bezahlen, sonst k&ouml;nnt ihr leider nicht teilnehmen.</p>
        <p><strong>Wann:</strong> 28.09 ab 12:00 Uhr <br /><strong>Wo:</strong> FH, am D Geb&auml;ude <br /><strong>Was mitbringen:</strong> Tasse/ Becher und Teller ggf, Picknickdecke bei gutem Wetter.</p>
        <p>Im Anschluss findet noch ein spannender Spieleabend mit Brettspielen und Quizshow statt.</p> <br/>
        <p>Wir freuen uns auf euch</p>';
        $event->type = 'event_registration';
        $event->registration_from = new DateTime('2024-09-23 8:00:00');
        $event->registration_to = new DateTime('2024-09-25 23:59:59');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 140;
        $event->form = '[
            {
                "$formkit": "select",
                "name": "eating_habit",
                "label": "Essgewohnheit",
                "options": {
                    "vegetarian": "Ich esse vegetarisch",
                    "vegan": "Ich esse vegan",
                    "all": "Ich esse alles"
                },
                "placeholder": "Bitte auswählen",
                "validation": "required"
            }
        ]';

        // save the event
        $event->save();
    }

    /**
     * Run the "Sport" event seeds.
     */
    public function runSport(): void
    {
        // create a new event
        $event = new Event;
        $event->name = 'Sport';
        $event->description = '<p>Auch sportliche Aktivitäten kommen bei uns nicht zu kurz. Für eine Anmeldegebühr von <strong>5€</strong> könnt ihr euch am Freitag auspowern.</p>
            <p>Bitte bezahlt die Anmeldegebühr am Montag zwischen 13:45 und 16:00 Uhr oder Mittwoch zwischen 12:00 und 14:30 Uhr im FSR. Solltet ihr bis Mittwoch nicht gezahlt haben, werden eure reservierten Plätze wieder freigegeben.</p>
            <p>Bitte beachtet auch die folgenden Hinweise zu den einzelnen Programmpunkten:</p>
            <p><strong>Fußball, Volleyball:</strong> Die Anmeldegebühr fungiert als Pfand, welches ihr beim Erscheinen wieder zurückbekommt.</p>
            <p><strong>Yoga:</strong> Bitte bringt eine eigene Yogamatte mit.</p>
            <p><strong>Bouldern:</strong> Falls nicht vorhanden, können Stoppersocken vor Ort für 3€ erworben werden.</p>
            <p><strong>Allgemein:</strong> Eine anschließende Teilnahme an weiteren Programmpunkten ist nur mit der “Foodtour” und dem "Tierpark Besuch" zeitlich möglich, und nicht für Teilnehmer der Boulder-Gruppe.</p>
            <p>Die genauen Treffpunkte und Zeiten posten wir rechtzeitig im Telegram Info Channel.</p>
            <p>Wir freuen uns auf euch!</p>';
        $event->type = 'slot_booking';
        $event->registration_from = new DateTime('2024-09-23 08:00:00');
        $event->registration_to = new DateTime('2024-09-25 23:59:00');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 150;

        // save the event
        $event->save();

        // create event slots
        $slots = [
            [
                'name' => 'Fußball, Volleyball',
                'has_requirements' => true,
                'maximum_participants' => 50,
            ],
            [
                'name' => 'Bouldern',
                'has_requirements' => true,
                'maximum_participants' => 43,
            ],
            [
                'name' => 'Yoga',
                'has_requirements' => true,
                'maximum_participants' => 50,
            ],
            [
                'name' => 'Lasertag',
                'has_requirements' => true,
                'maximum_participants' => 57,
            ],
        ];

        foreach ($slots as $slotData) {
            $slot = new Slot;
            $slot->name = $slotData['name'];
            $slot->event_id = $event->id;
            $slot->has_requirements = $slotData['has_requirements'];
            $slot->maximum_participants = $slotData['maximum_participants'];

            $slot->save();
        }
    }

    /**
     * Run the "Kultur" event seeds.
     */
    public function runKultur(): void
    {
        // check if event with name "Kultur" exists
        $event = Event::where('name', 'Kultur')->first();
        if ($event) {
            return;
        }

        // create a new event
        $event = new Event;
        $event->name = 'Kultur';
        $event->description = '<p>Die Stadt Aachen von einer etwas anderen Seite besser kennenlernen, Ziegen streicheln oder sich einfach den Bauch richtig voll schlagen?
            Auch das ist am Freitag in der Erstiwoche möglich.</p>
            <p>Bitte beachtet die folgenden Hinweise zu den einzelnen Programmpunkten:</p>
            <p><strong>Tierpark:</strong> Für eine Anmeldegebühr von <strong>5€</strong> ist eine Teilnahme möglich. Bitte bezahlt die Anmeldegebühr am Montag zwischen 13:45 und 16:00 Uhr oder Mittwoch zwischen 12:00 und 14:30 Uhr im FSR. Solltet ihr bis Mittwoch nicht gezahlt haben, werden eure reservierten Plätze wieder freigegeben. Die Anmeldegebühr fungiert als Pfand, welches ihr beim Erscheinen wieder zurückbekommt.</p>
            <p><strong>Foodtour:</strong> Ihr müsst eure Döner / Falafel-Taschen selber zahlen.</p>
            <p><strong>Allgemein:</strong> Eine vorab Teilnahme an weiteren Programmpunkten ist nur in Kombination mit “Fußball, Volleyball”, “Yoga” und “Lasertag” zeitlich möglich.</p>
            <p>Die genauen Treffpunkte und Zeiten posten wir rechtzeitig im Telegram Info Channel.</p>
            <p>Wir freuen uns auf euch!</p>';
        $event->type = 'slot_booking';
        $event->registration_from = new DateTime('2024-09-23 08:00:00');
        $event->registration_to = new DateTime('2024-09-25 23:59:00');
        $event->has_requirements = false;
        $event->consider_alcohol = false;
        $event->sort_order = 151;
        // save the event
        $event->save();

        // create event slots
        $slots = [
            [
                'name' => 'Foodtour',
                'has_requirements' => false,
                'maximum_participants' => 50,
            ],
            [
                'name' => 'Tierpark',
                'has_requirements' => false,
                'maximum_participants' => 50,
            ],
        ];

        foreach ($slots as $slotData) {
            $slot = new Slot;
            $slot->name = $slotData['name'];
            $slot->event_id = $event->id;
            $slot->has_requirements = $slotData['has_requirements'];
            $slot->maximum_participants = $slotData['maximum_participants'];

            $slot->save();
        }
    }
}
