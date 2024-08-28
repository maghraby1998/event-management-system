<div>
    <p>Hello {{$user->name}}, Please click verify to verify your email and be able to login</p>
    <a href="{{ route('verify.email', ['userId' => $user->id, 'token' => $token]) }}">Verify</a>
</div>