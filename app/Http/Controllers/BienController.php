<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Models\Bien;
use App\Repositories\BienRepository;
use App\Core\Data\ApiResponse;
use App\Core\Data\ErrorResponse;

class BienController extends Controller
{
    /**
     * Bien repository
     *
     * @var BienRepository
     */
    private $repository;
    private $userrepository;

    /**
     * Service constructor
     *
     * @param BienRepository $repository
     */
    public function __construct(BienRepository $repository, UserRepository $userrepository)
    {
        $this->repository = $repository;
        $this->userrepository = $userrepository;
    }

    public function createBien(Request $request)
    {
        $this->validate($request, [
            'articulo' => 'required|max:255',
            'descripcion' => 'required|max:255',
            'usuario_id' => "required|exists:users,id",
        ]);

        try {

            // Create the area in the database with empty url
            $bien = $this->repository->createOrUpdate($request->all());
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }

        return response()->json($bien);
    }

    public function updateBien(Request $request, $id)
    {
        $bien = Bien::findOrFail($id);

        $this->validate($request, [
            'articulo' => 'required|max:255',
            'descripcion' => 'required|max:255',
            'usuario_id' => "required|exists:users,id",
        ]);

        try {
            $bien = $this->repository->createOrUpdate($request->all(), ['id' => $bien->id]);
            return response()->json($bien);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }

    public function viewBien($id)
    {
        try {
            $bien = $this->repository->getByID($id, ["usuario"]);
            return response()->json($bien);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }


    //delete user(
    public function deleteBien($id)
    {
        try {
            $bien = $this->repository->delete($id);

            return response()->json(["message" => 'Removed successfully']);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }

    public function listBiens(Request $request)
    {
        try {
            $bien = $this->repository->getOnlyActives($request->all());
            return response()->json($bien);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }

    public function viewManyBien(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|array|exists:Bienes,id',
        ]);

        try {
            Log::error($request->only("id"));
            $bien = $this->repository->GetFromIds($request->only('id')["id"]);
            return response()->json($bien);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }

    public function seedBienes(Request $request)
    {
        try {
            $fecha = new \DateTime();
            $name = "Test";
            $username = "test".$fecha->getTimestamp();
            $password = "testpassword";
            $passwordHashed = app('hash')->make($password);

            // Create and return the new user
            $user = $this->userrepository->createOrUpdate([
                'name' => $name,
                'username' => $username,
                'password' => $passwordHashed
            ]);

            $filename = storage_path('app/BienSeed.csv');
            $file = fopen($filename,"r");

            $first = TRUE;

            while(! feof($file)) {
                $data = fgetcsv($file);

                if ($first) {
                    $first = FALSE;
                } else {
                    DB::table('Bienes')->insert([
                        [
                            'id' => $data[0],
                            "articulo" => $data[1],
                            "descripcion" => $data[2],
                            "usuario_id" => $user->id,
                            'updated_at' => $fecha,
                            'created_at' => $fecha
                        ]
                    ]);
                }
            }

            fclose($file);
            return response()->json(["message" => "Done!"]);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error happened!", [$e->getMessage()]), 400);
        }
    }
}
