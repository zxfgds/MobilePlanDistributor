<?php


use Phinx\Seed\AbstractSeed;

class RegionSeeder extends AbstractSeed
{
	/**
	 * Run Method.
	 *
	 * Write your database seeder using this method.
	 *
	 * More information on writing seeders is available here:
	 * https://book.cakephp.org/phinx/0/en/seeding.html
	 */
	public function run ()
	: void
	{
		$sql = file_get_contents(databasePath('sql/regions.sql'));
		$this->execute($sql);
	}
}
